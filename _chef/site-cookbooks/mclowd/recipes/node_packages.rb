["-g coffee-script", "dnode", "socket.io", "express", 'zombie'].each do |package|
  bash "install #{package}" do
    user "root"
    code "npm install #{package}"
  end
end   