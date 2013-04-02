class TaskForm
  constructor: (@budgets) ->

  updateTaskBudget: () ->
    type = $('input[name="task[type]"]:checked').val()    
    options = $(@budgets).filter("optgroup[label=#{type}]").html()
    if options
      $('#task_budget').html('<option value="">Select budget</option>' + options)      
    else
      $('#task_budget').empty()        
  
  updateBasedOnType: ->
    type = $('input[name="task[type]"]:checked').val()
    if type == 'fixed'
      $('#task_hoursPerWeek').parent().parent().hide()
    else  
      $('#task_hoursPerWeek').parent().parent().show()
    
    @updateTaskBudget()
    

  init: () =>
    self = @
    @updateBasedOnType()
    $('input[name="task[type]"]').change ->
      self.updateBasedOnType()
      

window.TaskForm = TaskForm

jQuery ->
  t = new TaskForm $('#task_budget').html()
  t.init()  