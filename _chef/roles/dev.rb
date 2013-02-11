name "Dev"
description "Base mclowd dev role."
 
run_list(
  "recipe[mclowd::dev]",
  "recipe[nodejs]",
  "recipe[nodejs::npm]"
)