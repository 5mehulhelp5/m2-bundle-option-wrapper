<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\BundleOptionWrapper\Catalog\Product\View\Type\Bundle;

use FeWeDev\Base\Variables;
use LogicException;
use Magento\Bundle\Model\Option as BundleOption;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Renders the element around a bundle option that identifies the option by its ID, type and title.
 */
class Option extends Template
{
    /**
     * Creates the block.
     *
     * @param Context $context
     * @param Variables $variables
     * @param array $data
     *
     * @phpstan-param array<string, mixed> $data
     */
    public function __construct(Context $context, protected Variables $variables, array $data = [])
    {
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Returns the bundle option to wrap.
     *
     * @return BundleOption
     * @throws LogicException
     */
    public function getBundleOption(): BundleOption
    {
        $bundleOption = $this->getData('bundle_option');

        if (! $bundleOption instanceof BundleOption) {
            throw new LogicException('No bundle option to wrap');
        }

        return $bundleOption;
    }

    /**
     * Returns the HTML of the bundle option to wrap.
     *
     * @return string
     */
    public function getBundleOptionHtml(): string
    {
        return $this->variables->stringValueOrDefault($this->getData('bundle_option_html'));
    }

    /**
     * Returns the ID of the bundle option.
     *
     * @return int
     */
    public function getBundleOptionId(): int
    {
        return $this->variables->intValue($this->getBundleOption()->getId());
    }

    /**
     * Returns the title of the bundle option as part of a CSS class.
     *
     * The title is in lower case, with spaces replaced by underscores and an underscore before upper case letters, for
     * example "gift__wrap" for "Gift Wrap" and "gift_wrap" for "GiftWrap".
     *
     * @return string
     */
    public function getBundleOptionTitle(): string
    {
        $title = preg_replace(
            '/\s/',
            '_',
            $this->variables->stringValueOrDefault($this->getBundleOption()->getTitle())
        );

        $title = preg_replace(
            '/[A-Z]([A-Z](?![a-z]))*/',
            '_$0',
            (string)$title
        );

        return ltrim(
            strtolower((string)$title),
            '_'
        );
    }

    /**
     * Returns the CSS classes of the wrapper.
     *
     * These are field-wrapper and one class each for the type and the title of the option.
     *
     * @return string[]
     */
    public function getWrapperClasses(): array
    {
        $classes = ['field-wrapper'];

        $names = [
            $this->variables->stringValueOrDefault($this->getBundleOption()->getType()),
            $this->getBundleOptionTitle()
        ];

        foreach ($names as $name) {
            if ($name !== '') {
                $classes[] = 'field-wrapper-' . $name;
            }
        }

        return $classes;
    }
}
