<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;

if( Loader::includeModule('iblock') === false ) die();

if( $this->StartResultCache() ) {

    $arResult = [];

    $arFields = [
        "ID",
        "NAME",
        "IBLOCK_ID",
    ];

    $arFilter =[
        "IBLOCK_TYPE"   => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID"     => $arParams["IBLOCK_ID"],
        "ACTIVE"        => "Y",
    ];

    if( is_array($arParams["FIELDS"]) )
        $arFields = array_merge($arFields,$arParams["FIELDS"]);

    if( is_array($arParams["PROPERTIES"]) )
        foreach ($arParams["PROPERTIES"] as $property)
            $arFields[] = "PROPERTY_" . $property;

    $res = CIBlockElement::GetList(
        [],
        $arFilter
        ,
        false,
        false,
        $arFields
    );

    unset($property);

    while ($arElement = $res->Fetch() ){

        $arButtons = CIBlock::GetPanelButtons(
            $arElement["IBLOCK_ID"],
            $arElement["ID"],
            0,
            array("SECTION_BUTTONS"=>false, "SESSID"=>false)
        );

        if( is_array($arParams["FIELDS"]) ){
            foreach ($arParams["FIELDS"] as $fields)
                if (strpos($fields, "PICTURE") === false)
                    $arFieldsResult[$fields] = $arElement[$fields];
                else
                    $arFieldsResult[$fields] = CFile::GetFileArray($arElement[$fields]);
        }else
            foreach ($arFields as $fields)
                $arFieldsResult[$fields] = $arElement[$fields];

        unset($fields);

        if( is_array($arParams["PROPERTIES"]) ) {
            foreach ($arParams["PROPERTIES"] as $property){
                if ( strpos($property, "PICTURE") === false && strpos($property, "GALLERY") == false) {
                    if(is_array($arElement["PROPERTY_".$property."_DESCRIPTION"]) )
                        foreach ($arElement["PROPERTY_".$property."_DESCRIPTION"] as $key=>$value)
                            $arFieldsResult[$property][$value] = $arElement["PROPERTY_".$property."_VALUE"][$key];
                    elseif( is_array($arElement["PROPERTY_".$property."_VALUE"]) )
                        foreach ($arElement["PROPERTY_".$property."_VALUE"] as $value)
                            $arFieldsResult[$property][] = $value;
                    else
                        $arFieldsResult[$property] = $arElement["PROPERTY_".$property."_VALUE"];
                }else{
                    if( is_array($arElement["PROPERTY_".$property."_VALUE"]) )
                        foreach ($arElement["PROPERTY_".$property."_VALUE"] as $idPicture)
                            $arFieldsResult[$property][] = CFile::GetFileArray($idPicture);
                    else
                        $arFieldsResult[$property] = CFile::GetFileArray($arElement["PROPERTY_".$property."_VALUE"]);
                }
            }
        }

        $arItem = [
            "ID"            => $arElement["ID"],
            "NAME"          => $arElement["NAME"],
            "IBLOCK_ID"     => $arElement["IBLOCK_ID"],
            "EDIT_LINK"     => $arButtons["edit"]["edit_element"]["ACTION_URL"],
            "DELETE_LINK"   => $arButtons["edit"]["delete_element"]["ACTION_URL"],
        ];

        $arResult["ITEMS"][] = array_merge($arItem,$arFieldsResult);
        unset($arItem,$arFieldsResult);
    }

    $this->IncludeComponentTemplate($componentPage);
}
