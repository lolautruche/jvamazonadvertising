{ezscript_require( array( 'ezjsc::jquery', 'jvamazonid.js' ) )}

<a name="jvamazonid_{$attribute.id}"></a>
{* ASIN field *}
<input id="ezcoa-{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}" 
       class="box ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" 
       type="text" size="70" 
       name="{$attribute_base}_jvamazonid_data_text_{$attribute.id}" 
       value="{$attribute.data_text|wash( xhtml )}" />
<br /><br />

{* Search button *}
<input class="button jvamazonid_search_button" type="submit" 
       id="jvamazonid_search_button-{$attribute.id}"
       name="CustomActionButton[{$attribute.id}_search_asin]"
       value="{"Search Amazon ID"|i18n( "design/standard/content/datatype/jvamazonid" )}" />

{* Allow search if empty checkbox *}
<input id="ezcoa-{$attribute_base}-{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_allow_search_empty" 
       class="ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" 
       type="checkbox" 
       name="{$attribute_base}_jvamazonid_asin_search_if_empty_{$attribute.id}"{if $attribute.data_int} checked="checked"{/if} /> 
<label style="display:inline;" for="ezcoa-{$attribute_base}-{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_allow_search_empty">
    {"Automatically search Amazon ID on publish if empty"|i18n( "design/standard/content/datatype/jvamazonid" )}
</label>

{* "Search Amazon ID" button has been clicked *}
{if is_set( $attribute.value.search_results )}
    {if $attribute.value.search_results|ne( -1 )}
    
    {* Redirect to the attribute anchor *}
    <script type="text/javascript">document.location = document.location+'#jvamazonid_{$attribute.id}';</script>
        
    <p>
        <strong>{'Product search results'} :</strong><br />
        <em>{'Click on the right product name to fill the field'}.</em>
    </p>
    <table class="list" cellspacing="0">
		<tr>
		    <th>{'Product name'|i18n( 'design/standard/content/datatype/jvamazonid' )}</th>
		    <th>{'Product image'|i18n( 'design/standard/content/datatype/jvamazonid' )}</th>
		    <th>{'Main category'|i18n( 'design/standard/content/datatype/jvamazonid' )}</th>
		    <th>{'Product group'|i18n( 'design/standard/content/datatype/jvamazonid' )}</th>
		    <th>{'Product link'|i18n( 'design/standard/content/datatype/jvamazonid' )}</th>
		</tr>
		{foreach $attribute.value.search_results as $product}
		
		<tr>
            <td><a href="javascript:;" class="jvamazonid_result" id="amazonproduct_{$attribute.id}_{$product.id}">{$product.title|wash}</a></td>
            <td>{if $product.image}<img src={$product.image|ezurl} />{/if}</td>
            <td>{$product.binding}</td>
            <td>{$product.productgroup}</td>
            <td><a href={$product.url|ezurl} target="_blank">{$product.url|shorten( 50 )}</a></td>
		</tr>
		{/foreach}
	</table>
    {else}
        
    <p><em>
    {'No product has been found in Amazon catalog for the search query "%searchquery"'|i18n( 'design/standard/content/datatype/jvamazonid',, hash( '%searchquery', $attribute.content.search_query ) )}
    </em></p>
    {/if}
{/if}
