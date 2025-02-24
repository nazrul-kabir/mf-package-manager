jQuery(document).ready(function($) {
    $('#package_postcodes').select2({
        placeholder: 'Select postcodes',
        allowClear: true,
        width: '100%'
    });

    // Handle 'All' option
    $('#package_postcodes').on('change', function(e) {
        var selectedValues = $(this).val();
        if (selectedValues && selectedValues.indexOf('all') !== -1) {
            $(this).val('all').trigger('change');
        }
    });
});
