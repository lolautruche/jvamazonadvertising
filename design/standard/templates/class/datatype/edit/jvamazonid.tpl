<div class="block">
    <fieldset>
	    <legend>
	        {"Reference field for ASIN search"|i18n( "design/standard/class/datatype/jvamazonid" )}
	    </legend>
	    
	    <label for="ContentClass_jvamazonid_asin_search_field_{$class_attribute.id}">
	       {"Enter attribute identifiers as for object name (ie. &lt;my_attribute_identifier&gt;)"|i18n( "design/standard/class/datatype/jvamazonid" )}
        </label>
	    <input type="text" size="50" 
	           name="ContentClass_jvamazonid_asin_search_field_{$class_attribute.id}" 
	           id="ContentClass_jvamazonid_asin_search_field_{$class_attribute.id}" 
	           value="{$class_attribute.data_text1}" />
	    <i>{"If left empty, will be content object name"|i18n( "design/standard/class/datatype/jvamazonid" )}</i>
	    
	    <br /><br />
	    <label for="ContentClass_jvamazonid_asin_search_if_empty_{$class_attribute.id}">
	       {"Automatically search ASIN on publish if object attribute is left empty (default value)"|i18n( "design/standard/class/datatype/jvamazonid" )}
        </label>
	    <input type="checkbox" 
               name="ContentClass_jvamazonid_asin_search_if_empty_{$class_attribute.id}" 
               id="ContentClass_jvamazonid_asin_search_if_empty_{$class_attribute.id}"{if $class_attribute.data_int1} checked="checked"{/if} />
    </fieldset>
    
    <fieldset>
        <legend>
            {"Amazon SearchIndex (category) to search into"|i18n( "design/standard/class/datatype/jvamazonid" )}
        </legend>
        
        <label for="ContentClass_jvamazonid_asin_search_index_{$class_attribute.id}">
           {"Enter a valid search index (product category)."|i18n( "design/standard/class/datatype/jvamazonid" )}<br />
        </label>
        <input type="text" size="50" 
               name="ContentClass_jvamazonid_asin_search_index_{$class_attribute.id}" 
               id="ContentClass_jvamazonid_asin_search_index_{$class_attribute.id}" 
               value="{$class_attribute.data_text2}" /><br />
           <i>{"Search indexes are listed here"|i18n( "design/standard/class/datatype/jvamazonid" )} : 
              <a href={"http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/APPNDX_SearchIndexValues.html"|ezurl} target="_blank">http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/APPNDX_SearchIndexValues.html</a>
           </i>
    </fieldset>
    
    <fieldset>
        <legend>
            {"Amazon BrowseNode (sub-category) to search into"|i18n( "design/standard/class/datatype/jvamazonid" )}
        </legend>
        
        <label for="ContentClass_jvamazonid_browsenode_{$class_attribute.id}">
           {"Enter a valid browse node (product location in Amazon catalog). Leave empty if not applicable"|i18n( "design/standard/class/datatype/jvamazonid" )}<br />
        </label>
        <input type="text" size="50" 
               name="ContentClass_jvamazonid_browsenode_{$class_attribute.id}" 
               id="ContentClass_jvamazonid_browsenode_{$class_attribute.id}" 
               value="{$class_attribute.data_int2}" /><br />
           <i>{"Find a browse nodes non-exhaustive list here"|i18n( "design/standard/class/datatype/jvamazonid" )} : 
              <a href={"http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/index.html?APPNDX_SearchIndexValues.html"|ezurl} target="_blank">http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/index.html?APPNDX_SearchIndexValues.html</a>
           </i>
    </fieldset>
</div>
