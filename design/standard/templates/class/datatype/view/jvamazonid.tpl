<div class="block">
    <label>{"Reference field for ASIN search"|i18n( "design/standard/class/datatype/jvamazonid" )} :</label>
    <p>{$class_attribute.data_text1|wash}</p>
    <label>{"Automatically search ASIN on publish if object attribute is left empty (default value)"|i18n( "design/standard/class/datatype/jvamazonid" )} :</label>
    <p>{$class_attribute.data_int1|choose( 'No'|i18n( "design/standard/class/datatype/jvamazonid" ), 'Yes'|i18n( "design/standard/class/datatype/jvamazonid" ) )}</p>
    <label>{"Amazon SearchIndex (category) to search into"|i18n( "design/standard/class/datatype/jvamazonid" )} :</label>
    <p>{$class_attribute.data_text2|wash}</p>
</div>
