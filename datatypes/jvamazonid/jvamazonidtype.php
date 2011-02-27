<?php
/**
 * File containing JVAmazonID datatype definition
 * @copyright Copyright (C) 2010 - Jerome Vieilledent. All rights reserved
 * @licence http://www.gnu.org/licenses/gpl-2.0.txt GNU GPLv2
 * @author Jerome Vieilledent
 * @version @@@VERSION@@@
 * @package jvamazonadvertising
 */
class JVAmazonIDType extends eZDataType
{
    const DATA_TYPE_STRING = 'jvamazonid';
    
    const CLASSATTRIBUTE_DEFAULT_FIELD = 'data_text1',
          CLASSATTRIBUTE_DEFAULT_EMPTY = '',
          CLASSATTRIBUTE_ALLOW_SEARCH_FIELD = 'data_int1',
          CLASSATTRIBUTE_ALLOW_SEARCH_DEFAULT = 0,
          CLASSATTRIBUTE_SEARCHINDEX_FIELD = 'data_text2',
          CLASSATTRIBUTE_SEARCHINDEX_DEFAULT = 'All', // Search in all Amazon categories (aka SearchIndex) by default
          CLASSATTRIBUTE_BROWSENODE_FIELD = 'data_int2', // BrowseNode (category id) to look into when performing a search
          CLASSATTRIBUTE_BROWSENODE_DEFAULT = '';
    
    const SEARCH_FIELD_VARIABLE = '_jvamazonid_asin_search_field_',
          ALLOW_SEARCH_IF_EMPTY_VARIABLE = '_jvamazonid_asin_search_if_empty_',
          SEARCH_INDEX_FIELD_VARIABLE = '_jvamazonid_asin_search_index_',
          CONTENT_FIELD_VARIABLE = '_jvamazonid_data_text_',
          BROWSENODE_FIELD_VARIABLE = '_jvamazonid_browsenode_';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::eZDataType( self::DATA_TYPE_STRING, 'Amazon ASIN searcher' );
    }
    
    // --------------------------------------
    // Methods concerning the CLASS attribute
    // --------------------------------------
    
    /**
     * Sets default values for a new class attribute.
     * @param eZContentClassAttribute $classAttribute
     * @return void
     */
    public function initializeClassAttribute( $classAttribute )
    {
        // Default value for search field
        if ( !$classAttribute->attribute( self::CLASSATTRIBUTE_DEFAULT_FIELD ) )
        {
            $classAttribute->setAttribute( self::CLASSATTRIBUTE_DEFAULT_FIELD,
                                           self::CLASSATTRIBUTE_DEFAULT_EMPTY );
                                           
        }
        
        // Default value for "Allow search if empty"
        if ( $classAttribute->attribute( self::CLASSATTRIBUTE_ALLOW_SEARCH_FIELD ) === null )
        {
            $classAttribute->setAttribute( self::CLASSATTRIBUTE_ALLOW_SEARCH_FIELD,
                                           self::CLASSATTRIBUTE_ALLOW_SEARCH_DEFAULT );
                                           
        }
        
        // Default value for search index
        if ( !$classAttribute->attribute( self::CLASSATTRIBUTE_SEARCHINDEX_FIELD ) )
        {
            $classAttribute->setAttribute( self::CLASSATTRIBUTE_SEARCHINDEX_FIELD,
                                           self::CLASSATTRIBUTE_SEARCHINDEX_DEFAULT );
        }
        
        // Default value for browse node
        if ( !$classAttribute->attribute( self::CLASSATTRIBUTE_BROWSENODE_FIELD ) )
        {
            $classAttribute->setAttribute( self::CLASSATTRIBUTE_BROWSENODE_FIELD,
                                           self::CLASSATTRIBUTE_BROWSENODE_DEFAULT );
        }
    }
    
	/**
     * Validates the input from the class definition form concerning this attribute.
     * @param eZHTTPTool $http
     * @param string $base Seems to be always 'ContentClassAttribute'.
     * @param eZContentClassAttribute $classAttribute
     * @return int eZInputValidator::STATE_ACCEPTED|eZInputValidator::STATE_INVALID|eZInputValidator::STATE_INTERMEDIATE
     */
    public function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        // TODO : Validate SearchIndex and BrowseNode validity
        return eZInputValidator::STATE_ACCEPTED;
    }
    
    /**
     * Fixes up the data that has been posted with the class edit form
     * This method is called only if validation method (self::validateClassAttributeHTTPInput()) returned eZInputValidator::STATE_INTERMEDIATE
     * @param eZHTTPTool $http
     * @param string $base POST variable name prefix (Always "ContentObjectAttribute")
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @see eZDataType::fixupClassAttributeHTTPInput()
     */
    public function fixupClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        
    }

    /**
     * Handles the input specific for one attribute from the class edit interface.
     * @param eZHTTPTool $http
     * @param string $base Seems to be always 'ContentClassAttribute'.
     * @param eZContentClassAttribute $classAttribute
     * @return void
     */
    public function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        // Search field(s)
        $searchFieldName = $base . self::SEARCH_FIELD_VARIABLE . $classAttribute->attribute( 'id' );
        if( $http->hasPostVariable( $searchFieldName ) )
        {
            $searchFieldValue = $http->postVariable( $searchFieldName );
            $classAttribute->setAttribute( self::CLASSATTRIBUTE_DEFAULT_FIELD, $searchFieldValue );
        }
        
        // Allow search if object attribute empty
        $searchIfEmptyFieldName = $base . self::ALLOW_SEARCH_IF_EMPTY_VARIABLE . $classAttribute->attribute( 'id' );
        if( $http->hasPostVariable( $searchIfEmptyFieldName ) ) // Checkbox : only set if posted
        {
            $classAttribute->setAttribute( self::CLASSATTRIBUTE_ALLOW_SEARCH_FIELD, 1 );
        }
        
        // Search index
        $searchIndexFieldName = $base . self::SEARCH_INDEX_FIELD_VARIABLE . $classAttribute->attribute( 'id' );
        if( $http->hasPostVariable( $searchIndexFieldName ) )
        {
            $searchIndexFieldValue = $http->postVariable( $searchIndexFieldName );
            $classAttribute->setAttribute( self::CLASSATTRIBUTE_SEARCHINDEX_FIELD, $searchIndexFieldValue );
        }
        
        // BrowseNode
        $browseNodeFieldName = $base . self::BROWSENODE_FIELD_VARIABLE . $classAttribute->attribute( 'id' );
        if( $http->hasPostVariable( $searchIndexFieldName ) )
        {
            $browseNodeFieldValue = $http->postVariable( $browseNodeFieldName );
            $classAttribute->setAttribute( self::CLASSATTRIBUTE_BROWSENODE_FIELD, $browseNodeFieldValue );
        }
    }
    
    /**
     * Returns the content for the class attribute
     * Result is an associative array :
     * 		- search_field
     * 		- search_index
     * @param eZContentClassAttribute $classAttribute
     * @return array
     * @see eZDataType::classAttributeContent()
     */
    public function classAttributeContent( $classAttribute )
    {
        $aContent = array(
            'search_field'		=> $classAttribute->attribute( self::CLASSATTRIBUTE_ALLOW_SEARCH_FIELD ),
            'search_index'		=> $classAttribute->attribute( self::CLASSATTRIBUTE_SEARCHINDEX_FIELD )
        );
        
        return $aContent;
    }
    
    // --------------------------------------
    // Methods concerning the OBJECT attribute
    // --------------------------------------
    
    /**
     * Initializes object attribute before displaying edit template
     * Can be useful to define default values. Default values can be defined in class attributes
     * @param eZContentObjectAttribute $contentObjectAttribute Object attribute for the new version
     * @param int $currentVersion Version number. NULL if this is the first version
     * @param eZContentObjectAttribute $originalContentObjectAttribute Object attribute of the previous version
     * @see eZDataType::initializeObjectAttribute()
     */
    public function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        // Sets default values on first version
        if( $currentVersion === null )
        {
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
            $allowSearchDefault = $contentClassAttribute->attribute( 'data_int1' );
            $contentObjectAttribute->setAttribute( 'data_int', $allowSearchDefault );
        }
    }

    /**
     * Validates input on content object level
     * Checks if entered Amazon ID is a valid ASIN
     * @param eZHTTPTool $http
     * @param string $base POST variable name prefix (Always "ContentObjectAttribute")
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return eZInputValidator::STATE_ACCEPTED|eZInputValidator::STATE_INVALID|eZInputValidator::STATE_INTERMEDIATE
     */
    public function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $fieldName = $base . self::CONTENT_FIELD_VARIABLE . $contentObjectAttribute->attribute( 'id' );
        $returnState = eZInputValidator::STATE_ACCEPTED;
        
        if( $http->hasPostVariable( $fieldName ) )
        {
            $fieldValue = $http->postVariable( $fieldName );
            if( trim( $fieldValue ) != '' )
            {
                $amazonResult = eZFunctionHandler::execute( 'amazonadvertising', 'item_lookup', array(
                    'id'		=> $fieldValue
                ) );
                
                if ( !$amazonResult instanceof JVAmazonAdvertisingAbstractItem )
                {
                    $validationError = ezpI18n::tr( 'extension/jvamazonadvertising/error', 'Invalid Amazon product ID' );
                    $contentObjectAttribute->setValidationError( $validationError );
                    $returnState = eZInputValidator::STATE_INVALID;
                }
            }
        }
        
        return $returnState;
    }
    
    /**
     * Fixes up the data that has been posted with the object edit form
     * This method is called only if validation method (self::validateObjectAttributeHTTPInput()) returned eZInputValidator::STATE_INTERMEDIATE
     * @param eZHTTPTool $http
     * @param string $base
     * @param eZContentObjectAttribute $objectAttribute
     * @see eZDataType::fixupObjectAttributeHTTPInput()
     */
    public function fixupObjectAttributeHTTPInput( $http, $base, $objectAttribute )
    {
        
    }

    /**
     * Fetches all variables from the object and handles them
     * Data store can be done here
     * @param eZHTTPTool $http
     * @param string $base POST variable name prefix (Always "ContentObjectAttribute")
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return true if fetching of object attributes is successful, false if not
     */
    public function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $fieldName = $base . self::CONTENT_FIELD_VARIABLE . $contentObjectAttribute->attribute( 'id' );
        $allowSearchFieldName = $base . self::ALLOW_SEARCH_IF_EMPTY_VARIABLE . $contentObjectAttribute->attribute( 'id' );
        
        if( $http->hasPostVariable( $fieldName ) )
        {
            $contentObjectAttribute->setAttribute( 'data_text', $http->postVariable( $fieldName ) );
        }
        
        if( $http->hasPostVariable( $allowSearchFieldName ) )
        {
            $contentObjectAttribute->setAttribute( 'data_int', 1 );
        }
        else
        {
            $contentObjectAttribute->setAttribute( 'data_int', 0 );
        }
        
        return true;
    }
    
    /**
     * Performs necessary actions with attribute data after object is published
     * If attribute content is empty, will try to get ASIN automatically by an AWS search
     * Returns true if the value was stored correctly
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param eZContentObject $contentObject The published object
     * @param array $publishedNodes
     * @return bool
     * @see eZDataType::onPublish()
     */
    public function onPublish( $contentObjectAttribute, $contentObject, $publishedNodes )
    {
        $content = $contentObjectAttribute->attribute( 'data_text' );
        $allowSearchIfEmpty = (bool)$contentObjectAttribute->attribute( 'data_int' );
        
        if( !$content && $allowSearchIfEmpty ) // No content stored, try to get ASIN automatically
        {
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            $searchPattern = $classAttribute->attribute( self::CLASSATTRIBUTE_DEFAULT_FIELD );
            $searchQuery = $this->getSearchQuery( $searchPattern, $contentObject->attribute( 'id' ) );
            $aAmazonResult = $this->doSearch( $searchQuery, $contentObjectAttribute );
            
            if( count( $aAmazonResult ) > 0 )
            {
                $contentObjectAttribute->setAttribute( 'data_text', $aAmazonResult[0]->id );
                $contentObjectAttribute->store();
                return true;
            }
        }
    }
    
    /**
     * Checks if current content object attribute has content
     * Returns true if it has content
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return bool
     * @see eZDataType::hasObjectAttributeContent()
     */
    public function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return trim( $contentObjectAttribute->attribute( 'data_text' ) ) != '';
    }

    /**
     * Returns the content.
     * Result is an associative array :
     * 		- search_query : The search query built from the search pattern filled in the class attribute
     * 		- search_pattern : The search pattern as filled in the class attribute
     * 		- search_index : The search index as filled in the class attribute (ie. MP3Downloads)
     * 		- product_id : The Amazon product ID (ASIN, ISBN, ...)
     * @param eZContentObjectAttribute
     * @return array
     */
    public function objectAttributeContent( $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        $searchPattern = $classAttribute->attribute( self::CLASSATTRIBUTE_DEFAULT_FIELD );
        $aContent = array(
            'search_query'		=> $this->getSearchQuery( $searchPattern, $contentObjectAttribute->attribute( 'contentobject_id' ) ),
        	'search_pattern'	=> $searchPattern,
            'search_index'		=> $classAttribute->attribute( self::CLASSATTRIBUTE_SEARCHINDEX_FIELD ),
            'product_id'		=> $contentObjectAttribute->attribute( 'data_text' )
        );
        
        return $aContent;
    }

    /**
     * Returns the meta data used for search index.
     * @param eZContentObjectAttribute
     * @return string
     */
    public function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /**
     * Returns the value as it will be shown if this attribute is used in the object name pattern.
     * @param eZContentObjectAttribute
     * @name string
     * @return string
     */
    public function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /**
     * @return true if the datatype can be indexed
     */
    public function isIndexable()
    {
        return true;
    }
    
    /**
     * Initializes the object attribute from a string representation
     * @param eZContentObjectAttribute
     * @param string
     * @see eZDataType::fromString()
     */
    public function fromString( $objectAttribute, $string )
    {
        $objectAttribute->setAttribute( 'data_text', $string );
    }
    
    /**
     * Returns the string representation of the object attribute
     * @param eZContentObjectAttribute
     * @see eZDataType::toString()
     * @return string
     */
    public function toString( $objectAttribute )
    {
        return $objectAttribute->attribute( 'data_text' );
    }
    
    /**
     * Returns the sort type. Can be 'string', 'int' ('float' is not supported) or false if sorting is not supported
     * @see eZDataType::sortKeyType()
     */
    public function sortKeyType()
    {
        return 'string';
    }
    
    /**
     * Returns the sort key, for sorting at the attribute level
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return string
     * @see eZDataType::sortKey()
     */
    public function sortKey( $contentObjectAttribute )
    {
        return strtolower( $contentObjectAttribute->attribute( 'data_text' ) );
    }
    
    /**
     * Returns search query for object attribute with class attribute search pattern
     * @param string $searchPattern
     * @param int $contentObjectID
     * @return string
     */
    private function getSearchQuery( $searchPattern, $contentObjectID )
    {
        $contentObject = eZContentObject::fetch( $contentObjectID );
        $searchValue = '';
        preg_match_all( '#<([^>]+)>#U', $searchPattern, $matches, PREG_SET_ORDER ); // Get all attribute identifiers in searh pattern (in the form of <attribute_identifier>)
            
        if( count( $matches ) > 0 )
        {
            $searchValue = $searchPattern;
            $dataMap = $contentObject->dataMap();
            // Loop against all matches and check if they are attribute identifiers for this content object
            // If not, just remove them
            foreach( $matches as $match )
            {
                list( $matchPattern, $identifier ) = $match;
                if( isset( $dataMap[$identifier] ) )
                {
                    $searchValue = str_replace( $matchPattern, $dataMap[$identifier]->title(), $searchValue );
                }
                else
                {
                    $searchValue = str_replace( $matchPattern, '', $searchValue );
                }
            }
            
            $searchValue = trim( $searchValue );
        }
        
        return $searchValue;
    }
    
    /**
     * Do the ASIN search in Amazon catalog
     * @param string $searchQuery
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return array Array of objects implementing IJVAmazonAdvertisingItem (see amazonadvertising.ini for exact class)
     */
    private function doSearch( $searchQuery, eZContentObjectAttribute $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        
        // Now perform an ItemSearch
        $aItemSearchParams = array(
            'keywords'      => $searchQuery,
            'search_index'  => $classAttribute->attribute( self::CLASSATTRIBUTE_SEARCHINDEX_FIELD )
        );
        
        // Is there a BrowseNode to look into ?
        if( $classAttribute->attribute( self::CLASSATTRIBUTE_BROWSENODE_FIELD ) > 0 )
        {
            $aRawParams = array(
                'BrowseNode'    => $classAttribute->attribute( self::CLASSATTRIBUTE_BROWSENODE_FIELD )
            );
            $aItemSearchParams['raw_params'] = $aRawParams;
        }
        
        $aAmazonResult = eZFunctionHandler::execute( 'amazonadvertising', 'item_search', $aItemSearchParams );
        return $aAmazonResult;
    }
    
    /**
     * Handle actions not directly related to content object publication,
     * like an image deletion on an attribute with eZImage datatype,
     * or like adding a new row on a matrix attribute
     * @see kernel/classes/eZDataType::customObjectAttributeHTTPAction()
     * @param eZHTTPTool $http
     * @param string $action The action name
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param array $parameters Associative array
     *                              - module (reference to the content module currently in use)
     *                              - current-redirection-uri (URI that will be used to redirect the user after the custom action has been performed)
     *                              - base_name (Usually set as ContentObjectAttribute)
     */
    public function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
    {
        if( $action == 'search_asin' )
        {
            $contentObjectID = $contentObjectAttribute->object()->attribute( 'id' );
            eZContentObject::clearCache( array( $contentObjectID ) ); // Clear in-memory cache to get full datamap. Will be truncated otherwise
            
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            $searchPattern = $classAttribute->attribute( self::CLASSATTRIBUTE_DEFAULT_FIELD );
            $searchQuery = $this->getSearchQuery( $searchPattern, $contentObjectID );
            $aAmazonResult = $this->doSearch( $searchQuery, $contentObjectAttribute );
            if( count( $aAmazonResult ) > 0 )
            {
                // Store results in HTTP value which is temporary and accessible through "value" attribute in templates
                // $attribute.value
                $contentObjectAttribute->setHTTPValue( array( 'search_results' => $aAmazonResult ) );
            }
            else
            {
                $contentObjectAttribute->setHTTPValue( array( 'search_results' => -1 ) );
            }
        }
    }
}

eZDataType::register( JVAmazonIDType::DATA_TYPE_STRING, 'JVAmazonIDType' );
