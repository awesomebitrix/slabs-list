<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader,
    Bitrix\Main\Data\Cache;

if( Loader::includeModule('iblock') === false ) die();

$cache_id = md5(serialize($arParams));
$cache_dir = "/slabs";

$obCache = Cache::createInstance();

if($obCache->InitCache(36000, $cache_id, $cache_dir)) {
    $arResult = $obCache->GetVars();
}elseif($obCache->StartDataCache()) {
    global $CACHE_MANAGER;

    $res = CIBlockElement::GetList(
        [],
        [
            "IBLOCK_TYPE"   => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID"     => $arParams["IBLOCK_ID"],
            "ACTIVE"        => "Y",
        ],
        false,
        false,
        [
            "ID",
            "NAME",
            "PREVIEW_PICTURE",
        ]
    );

    $CACHE_MANAGER->StartTagCache($cache_dir);

    while ( $arElement = $res->Fetch() ) {

        $CACHE_MANAGER->RegisterTag("iblock_id_".$arElement["IBLOCK_ID"]);

        $arButtons = CIBlock::GetPanelButtons(
            $arParams["IBLOCK_ID"],
            $arElement["ID"],
            0,
            array("SECTION_BUTTONS"=>false, "SESSID"=>false)
        );

        $arResult["ITEMS"][] = [
            "ID"            => $arElement["ID"],
            "NAME"          => $arElement["NAME"],
            "PICTURE"       => CFile::GetFileArray($arElement["PREVIEW_PICTURE"])["SRC"],
            "EDIT_LINK"     => $arButtons["edit"]["edit_element"]["ACTION_URL"],
            "DELETE_LINK"   => $arButtons["edit"]["delete_element"]["ACTION_URL"],
        ];
    }

    $CACHE_MANAGER->RegisterTag("iblock_id_new");
    $CACHE_MANAGER->EndTagCache();

    $obCache->EndDataCache($arResult);
}else {
    $arResult = [];
}


$this->IncludeComponentTemplate($componentPage);
