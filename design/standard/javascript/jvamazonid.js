$(document).ready(function() {
    $('a.jvamazonid_result').click(function() {
        var aID = $(this).attr('id').split('_');
        var attributeID = aID[1];
        var asin = aID[2];
        var nameAttribute = 'ContentObjectAttribute_jvamazonid_data_text_'+attributeID;
        
        $('input[name="'+nameAttribute+'"]').val(asin);
    });
});