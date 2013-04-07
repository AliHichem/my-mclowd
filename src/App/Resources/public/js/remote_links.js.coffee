class RemoteLink 
  constructor: () ->
    self = @
    $(document).delegate 'a[data-method], a[data-confirm]', 'click', (e) -> 
      e.preventDefault()

      link = $(@)
      method = link.data('method')
      data = link.data('params')
      href = link.attr('href')
      confirm_message = link.data('confirm')
      confirm_message = 'Are You sure?' if not confirm_message
      if link.data('confirm') 
        return false if not self.confirm_message(confirm_message) 
        self.handleRequest(href, method)
      else
        self.handleRequest(href, method)  
  
  confirm_message: (message) ->
    confirm(message)                
  
  handleRequest: (url, method) ->
    form = $('<form method="post" action="' + url + '"></form>');
    metadata_input = '<input name="_method" value="' + method + '" type="hidden" />';
    form.hide().append(metadata_input).appendTo('body');
    form.submit();    

window.RemoteLink = RemoteLink

jQuery ->
  new RemoteLink