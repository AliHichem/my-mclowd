$ ->
    form = $('form#user_profile')
    file_input = $('input#user_profile_picture', form)
    picture = $('img#profile-image', form)
    if picture.size()
        file_input.parent('div').toggleClass('invisible')
        picture.click(-> file_input.click())
        file_input.change ->
            picture.toggleClass('invisible')
            file_input.parent('div').toggleClass('invisible')
            show_form()
    window.picture = picture
    window.file_input = file_input
    form_visible = no
    submit = $('input:submit', form)
    prev_val = submit.val()
    submit.val('Edit')
    show_form = ->
        if form_visible then true else
            $('.hidden-fields, .overlay', form).toggleClass('invisible')
            submit.val(prev_val)
            form_visible = true
            false
    submit.click show_form
