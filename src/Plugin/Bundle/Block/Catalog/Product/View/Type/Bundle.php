<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\BundleOptionWrapper\Plugin\Bundle\Block\Catalog\Product\View\Type;

use Infrangible\BundleOptionWrapper\Helper\Data;
use Magento\Bundle\Model\Option;

/**
 * Wraps the HTML of every bundle option on the product page.
 */
class Bundle
{
    /**
     * Creates the plugin.
     *
     * @param Data $helper
     */
    public function __construct(protected Data $helper)
    {
    }

    /**
     * Wraps the HTML of the option.
     *
     * @param \Magento\Bundle\Block\Catalog\Product\View\Type\Bundle $subject
     * @param string $result
     * @param Option $bundleOption
     *
     * @return string
     */
    public function afterGetOptionHtml(
        \Magento\Bundle\Block\Catalog\Product\View\Type\Bundle $subject,
        string $result,
        Option $bundleOption
    ): string {
        return $this->helper->renderWrapper(
            $subject,
            $bundleOption,
            $result
        );
    }
}
