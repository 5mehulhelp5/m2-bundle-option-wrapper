<?php

declare(strict_types=1);

namespace Infrangible\BundleOptionWrapper\Plugin\Bundle\Block\Catalog\Product\View\Type;

use Infrangible\BundleOptionWrapper\Helper\Data;
use Magento\Bundle\Model\Option;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Bundle
{
    /** @var Data */
    protected $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

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
