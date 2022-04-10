<?php

namespace Amasty\SecondIlyaRog\Plugin;

class ChangeAddCart {
    public function afterGetFormAction(
        \Amasty\IlyaRog\Block\Form $subject,
        $result
    ) {
        return $result = ('checkout/cart/add');
    }
}
