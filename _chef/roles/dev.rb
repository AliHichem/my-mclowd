name "Dev"
description "Base mclowd dev role."
 
run_list(
  "recipe[nodejs]",
  "recipe[nodejs::npm]",
  "recipe[mclowd::dev]"
)