<?php

declare(strict_types=1);

namespace Infrangible\BundleOptionWrapper\Catalog\Product\View\Type\Bundle;

use Magento\Framework\View\Element\Template;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Option extends Template
{
    public function getBundleOption(): \Magento\Bundle\Model\Option
    {
        return $this->getData('bundle_option');
    }

    public function getBundleOptionHtml(): string
    {
        return $this->getData('bundle_option_html');
    }

    public function getBundleOptionTitle(): string
    {
        return ltrim(
            strtolower(
                preg_replace(
                    '/[A-Z]([A-Z](?![a-z]))*/',
                    '_$0',
                    preg_replace(
                        '/\s/',
                        '_',
                        $this->getBundleOption()->getTitle()
                    )
                )
            ),
            '_'
        );
    }
}
