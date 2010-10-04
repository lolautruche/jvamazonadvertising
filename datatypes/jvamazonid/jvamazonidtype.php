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
          CLASSATTRIBUTE_SEARCHINDEX_DEFAULT = 'All'; // Search in all Amazon categories (aka SearchIndex) by default
    
    const SEARCH_FIELD_VARIABLE = '_jvamazonid_asin_search_field_',
          ALLOW_SEARCH_IF_EMPTY_VARIABLE = '_jvamazonid_asin_search_if_empty_',
          SEARCH_INDEX_FIELD_VARIABLE = '_jvamazonid_asin_search_index_',
          CONTENT_FIELD_VARIABLE = '_jvamazonid_data_text_';

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
    public function fixupClassAttributeHTTPInput($http, $base, $classAttribute)
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
     * @return true if fetching of class attributes are successfull, false if not
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
        $content = $contentObjectAttribute->content();
        $allowSearchIfEmpty = (bool)$contentObjectAttribute->attribute( 'data_int' );
        
        if( !$content && $allowSearchIfEmpty ) // No content stored, try to get ASIN automatically
        {
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            $searchPattern = $classAttribute->attribute( self::CLASSATTRIBUTE_DEFAULT_FIELD );
            $searchValue = $searchPattern;
            preg_match_all( '#<([^>]+)>#U', $searchPattern, $matches, PREG_SET_ORDER ); // Get all attribute identifiers in searh pattern (in the form of <attribute_identifier>)
            
            if( count( $matches ) > 0 )
            {
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
            
            // Now perform an ItemSearch
            $aItemSearchParams = array(
                'keywords'		=> $searchValue,
                'search_index'	=> $classAttribute->attribute( self::CLASSATTRIBUTE_SEARCHINDEX_FIELD )
            );
            $aAmazonResult = eZFunctionHandler::execute( 'amazonadvertising', 'item_search', $aItemSearchParams );
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
     * @param eZContentObjectAttribute
     * @return string
     */
    public function objectAttributeContent( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /**
     * Returns the meta data used for storing search indeces.
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

}

eZDataType::register( JVAmazonIDType::DATA_TYPE_STRING, 'JVAmazonIDType' );
