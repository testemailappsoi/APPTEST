<script>
    $(document).on('change', '#fonc_Appli,#fonc_control', function(){
    let $field = $(this)
    let $form = $field.closest('form')
    let data = {}
    data[$field.attr('name')] = $field.val()
    $.post($form.attr('action'),$form.attr('method') ,data,
        complete: function (html){
        $('#fonc_control').replaceWith($(html.responseText).find('#fonc_control'))
    })
})
</script>