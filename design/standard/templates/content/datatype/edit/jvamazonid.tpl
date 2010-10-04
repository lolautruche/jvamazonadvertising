{* ASIN field *}
<input id="ezcoa-{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}" 
       class="box ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" 
       type="text" size="70" 
       name="{$attribute_base}_jvamazonid_data_text_{$attribute.id}" 
       value="{$attribute.data_text|wash( xhtml )}" />
<br /><br />

{* Search button *}
<input class="button jvamazonid_search_button" type="button" 
       id="jvamazonid_search_button-{$attribute.contentclassattribute_id}"
       value="{"Search Amazon ID"|i18n( "design/standard/class/datatype/jvamazonid" )}" />
{* Allow search if empty checkbox *}
<input id="ezcoa-{$attribute_base}-{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_allow_search_empty" 
       class="ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" 
       type="checkbox" 
       name="{$attribute_base}_jvamazonid_asin_search_if_empty_{$attribute.id}"{if $attribute.data_int} checked="checked"{/if} /> 
<label style="display:inline;" for="ezcoa-{$attribute_base}-{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_allow_search_empty">
    {"Automatically search Amazon ID on publish if empty"|i18n( "design/standard/class/datatype/jvamazonid" )}
</label>
