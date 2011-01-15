{if $attribute.has_content}
    {def $amazonItem = fetch( 'amazonadvertising', 'item_lookup', hash( 'id', $attribute.data_text ) )}
    <a href={$amazonItem.url|ezurl} target="_blank">{$attribute.data_text}</a>{if $amazonItem.binding} ({$amazonItem.binding}){/if}
{/if}