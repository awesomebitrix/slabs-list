# slabs-list
Подключение компонента:
```
<? $APPLICATION->IncludeComponent(
    "slabs:list",
    "",
    Array(
        "IBLOCK_TYPE"   => "catalog",
        "IBLOCK_ID"     => 1,
        "FILTER_NAME"   => "arrFilter",
        "CACHE_TIME"    => 36000000,
        "CACHE_TYPE"    => "A",
        "FIELDS"        => [
            "DETAIL_TEXT"
        ],
        "PROPERTIES"     => [
            "format",
        ]
    )
); ?>
```
FIELDS -  Список полей.    
Всегда по умолчанию добавляются "NAME", "IBLOCK_ID", "ID"

PROPERTIES - Список свойств ИБ.
Если свойства явялется фотографией, название должно содержать "PICTURE" или "GALLERY", 
для того что бы был получен массив картинки, а не ее ID 

!!! ВАЖНО !!!
Что бы множественные свойства отрабатывались адекватно, в настройках ИБ должно быть "Значения свойств хранятся:в отдельной таблице для данного информационного блока"