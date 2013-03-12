// Generated by CoffeeScript 1.4.0
(function() {
  var TaskForm,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  TaskForm = (function() {

    function TaskForm(budgets) {
      this.budgets = budgets;
      this.init = __bind(this.init, this);

    }

    TaskForm.prototype.updateTaskBudget = function() {
      var options, type;
      type = $('input[name="task[type]"]:checked').val();
      options = $(this.budgets).filter("optgroup[label=" + type + "]").html();
      if (options) {
        return $('#task_budget').html('<option value="">Select budget</option>' + options);
      } else {
        return $('#task_budget').empty();
      }
    };

    TaskForm.prototype.updateBasedOnType = function() {
      var type;
      type = $('input[name="task[type]"]:checked').val();
      if (type === 'fixed') {
        $('#task_hoursPerWeek').parent().parent().hide();
      } else {
        $('#task_hoursPerWeek').parent().parent().show();
      }
      return this.updateTaskBudget();
    };

    TaskForm.prototype.init = function() {
      var self;
      self = this;
      this.updateBasedOnType();
      return $('input[name="task[type]"]').change(function() {
        return self.updateBasedOnType();
      });
    };

    return TaskForm;

  })();

  window.TaskForm = TaskForm;

  jQuery(function() {
    var t;
    t = new TaskForm($('#task_budget').html());
    return t.init();
  });

}).call(this);
