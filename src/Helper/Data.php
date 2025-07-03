<?php

declare(strict_types=1);

namespace Infrangible\BundleOptionWrapper\Helper;

use Magento\Bundle\Model\Option;
use Magento\Framework\View\Element\AbstractBlock;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Data
{
    public function renderWrapper(
        AbstractBlock $block,
        Option $bundleOption,
        string $bundleOptionHtml,
        string $wrapperBlockName = 'bundle_option_wrapper'
    ): string {
        $block = $block->getChildBlock($wrapperBlockName);

        if ($block) {
            $block->setData(
                'bundle_option',
                $bundleOption
            );

            $block->setData(
                'bundle_option_html',
                $bundleOptionHtml
            );

            return $block->toHtml();
        } else {
            return $bundleOptionHtml;
        }
    }
}
