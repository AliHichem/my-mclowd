name "search"
description "Elasticsearch node."
 
run_list(
  "recipe[java]",
  "recipe[elasticsearch]"
)

default_attributes(
   "java" => {
      "oracle" => {
        "accept_oracle_download_terms" => true
      }
   }
)

override_attributes(
  "java" => {
    "install_flavor" => "oracle"
  }
)