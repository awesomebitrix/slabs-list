<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="f-social">
    <span>Мы в соц. сетях</span>
    <? foreach ($arResult["ITEMS"] as $item) { ?>
        <? $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT")); ?>
        <? $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => 'Будет удалена вся информация, связанная с этой записью. Продолжить?')); ?>
        <a href="/" target="_blank" id="<?= $this->GetEditAreaId($arItem['ID']); ?>"></a>
    <? } ?>
</div>