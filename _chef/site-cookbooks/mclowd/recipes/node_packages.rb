["-g coffee-script", "dnode", "socket.io", "express"].each do |package|
  bash "install #{package}" do
    user "root"
    code "npm install #{package}"
  end
end   