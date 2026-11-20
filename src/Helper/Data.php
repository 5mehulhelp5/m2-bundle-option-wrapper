<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\BundleOptionWrapper\Helper;

use Magento\Bundle\Model\Option;
use Magento\Framework\View\Element\AbstractBlock;

/**
 * Wraps the HTML of bundle options with the wrapper block, also in the option lists of other modules.
 */
class Data
{
    /**
     * Renders the option HTML with the wrapper block that is a child of the block.
     *
     * Without such a child, the option HTML is returned unchanged.
     *
     * @param AbstractBlock $block
     * @param Option $bundleOption
     * @param string $bundleOptionHtml
     * @param string $wrapperBlockName
     *
     * @return string
     */
    public function renderWrapper(
        AbstractBlock $block,
        Option $bundleOption,
        string $bundleOptionHtml,
        string $wrapperBlockName = 'bundle_option_wrapper'
    ): string {
        $wrapperBlock = $block->getChildBlock($wrapperBlockName);

        if (! $wrapperBlock instanceof AbstractBlock) {
            return $bundleOptionHtml;
        }

        $wrapperBlock->setData(
            'bundle_option',
            $bundleOption
        );

        $wrapperBlock->setData(
            'bundle_option_html',
            $bundleOptionHtml
        );

        return $wrapperBlock->toHtml();
    }
}
