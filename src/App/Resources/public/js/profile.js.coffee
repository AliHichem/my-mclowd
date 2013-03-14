class HiddenField
    constructor: (@container) ->
        @hidden = yes
        @overlay = $('.overlay', container)
        @field = $('.hidden-fields', container)
        @button = $('.edit-button', container)
        @button.click(=> @uncover())
    uncover: ->
        @hidden = not @hidden
        console.log(this)
        el.toggleClass('invisible') for el in [
            @overlay
            @field
            @button
        ]
        false

$ ->
    form = $('form#user_profile')
    new HiddenField($(cls, form)) for cls in [
        '.image-field'
        '.name-field'
        '.location-fields'
    ]
