<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\BundleOptionWrapper\Test\Unit\Catalog\Product\View\Type\Bundle;

use FeWeDev\Base\Variables;
use Infrangible\BundleOptionWrapper\Catalog\Product\View\Type\Bundle\Option;
use LogicException;
use Magento\Bundle\Model\Option as BundleOption;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\TemplateEngine\Php;
use Magento\Framework\ZendEscaper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Tests the wrapper of bundle options: its CSS classes and that the template escapes the values of the option.
 */
class OptionTest extends TestCase
{
    /**
     * Returns a bundle option with the ID 12 and the title and type, as strings like Magento loads them.
     *
     * @param string|null $title
     * @param string|null $type
     *
     * @return BundleOption
     */
    private function createBundleOption(?string $title, ?string $type): BundleOption
    {
        $option = $this->createStub(BundleOption::class);

        $option->method('getId')->willReturn('12');
        $option->method('getTitle')->willReturn($title);
        $option->method('getType')->willReturn($type);

        return $option;
    }

    /**
     * Returns the wrapper block with the option and its HTML.
     *
     * @param BundleOption|null $option
     * @param string|null $optionHtml
     *
     * @return Option
     */
    private function createBlock(?BundleOption $option, ?string $optionHtml = null): Option
    {
        return new Option(
            $this->createStub(Context::class),
            new Variables(),
            [
                'bundle_option'      => $option,
                'bundle_option_html' => $optionHtml
            ]
        );
    }

    /**
     * Returns option titles and the title in the CSS class.
     *
     * @return array
     * @phpstan-return array<string, array{string|null, string}>
     */
    public static function getOptionTitles(): array
    {
        return [
            'words'          => ['Gift Wrap', 'gift__wrap'],
            'camel case'     => ['GiftWrap', 'gift_wrap'],
            'lower case'     => ['size', 'size'],
            'abbreviation'   => ['XL Size', 'xl__size'],
            'no title'       => [null, '']
        ];
    }

    /**
     * Tests the title of the option in the CSS class.
     *
     * @dataProvider getOptionTitles
     *
     * @param string|null $title
     * @param string $expected
     *
     * @return void
     */
    #[DataProvider('getOptionTitles')]
    public function testGetOptionTitle(?string $title, string $expected): void
    {
        $block = $this->createBlock($this->createBundleOption($title, 'select'));

        $this->assertSame($expected, $block->getBundleOptionTitle());
    }

    /**
     * Tests the CSS classes for the type and title of the option.
     *
     * @return void
     */
    public function testGetWrapperClasses(): void
    {
        $block = $this->createBlock($this->createBundleOption('Gift Wrap', 'checkbox'));

        $this->assertSame(
            ['field-wrapper', 'field-wrapper-checkbox', 'field-wrapper-gift__wrap'],
            $block->getWrapperClasses()
        );
    }

    /**
     * Tests that an option without title gets no empty class.
     *
     * @return void
     */
    public function testGetWrapperClassesWithoutTitle(): void
    {
        $block = $this->createBlock($this->createBundleOption(null, 'radio'));

        $this->assertSame(['field-wrapper', 'field-wrapper-radio'], $block->getWrapperClasses());
    }

    /**
     * Tests the ID of the option, which Magento loads as string.
     *
     * @return void
     */
    public function testGetBundleOptionId(): void
    {
        $block = $this->createBlock($this->createBundleOption('Size', 'select'));

        $this->assertSame(12, $block->getBundleOptionId());
    }

    /**
     * Tests that the block without option is a programming error.
     *
     * @return void
     */
    public function testGetBundleOptionWithoutOption(): void
    {
        $this->expectException(LogicException::class);

        $this->createBlock(null)->getBundleOption();
    }

    /**
     * Tests that the template escapes the title of the option, which admin users enter, and outputs the HTML of the
     * option unchanged.
     *
     * @return void
     * @throws Throwable
     */
    public function testTemplateEscapesOptionValues(): void
    {
        $block = $this->createBlock(
            $this->createBundleOption('Size"><script>alert(1)</script>', 'select'),
            '<select name="bundle_option[12]"></select>'
        );

        $html = $this->renderTemplate($block);

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('id="bundle-option-12"', $html);
        $this->assertStringContainsString('data-option-id="12"', $html);
        $this->assertStringContainsString('<select name="bundle_option[12]"></select>', $html);
    }

    /**
     * Renders the template of the wrapper with Magento's PHP template engine, the block and a real escaper, as Magento
     * does.
     *
     * @param Option $block
     *
     * @return string
     * @throws Throwable
     */
    private function renderTemplate(Option $block): string
    {
        // Magento's escaper gets the inline translation and the Laminas escaper from the object manager
        $objectManager = $this->createStub(ObjectManagerInterface::class);
        $objectManager->method('get')->willReturnCallback($this->createObject(...));
        ObjectManager::setInstance($objectManager);

        return (new Php($objectManager))->render(
            $block,
            __DIR__ . '/../../../../../../../view/frontend/templates/catalog/product/view/type/bundle/options/' .
            'option.phtml',
            ['block' => $block, 'escaper' => new Escaper()]
        );
    }

    /**
     * Returns the real Laminas escaper, and a stub of other classes, as the object manager does.
     *
     * @param string $type
     * @phpstan-param class-string $type
     *
     * @return object
     */
    private function createObject(string $type): object
    {
        return $type === ZendEscaper::class ? new ZendEscaper() : $this->createStub($type);
    }
}
