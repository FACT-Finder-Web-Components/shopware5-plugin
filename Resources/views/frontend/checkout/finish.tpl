{extends file="parent:frontend/checkout/finish.tpl"}

{block name="frontend_checkout_finish_items" }
  {$smarty.block.parent}
  <ff-checkout-tracking>
    {foreach $sBasket.content as $key => $sBasketItem}
      <ff-checkout-tracking-item
              record-id="{$sBasketItem.ordernumber}"
              count=" {$sBasketItem.quantity}"
              channel="{config name=ffChannel}">
      </ff-checkout-tracking-item>
    {/foreach}
  </ff-checkout-tracking>
{/block}
