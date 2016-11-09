var app = require('http').createServer(handler)
var io = require('socket.io')(app);
var fs = require('fs');
var mysql = require("mysql");

app.listen(80);
console.log("App running on port: 80");




// =======================================================
//  DATABASE
// =======================================================

var mysql = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "dothatthang",
  database : 'khadimfall'
});
mysql.connect(function(err){
  if(err){
    console.log('Error connecting to Db:'+err);
    return;
  }
  console.log("Mysql-Connection established");
});


// =======================================================
//  SOCKET
// =======================================================

function handler (req, res) {
  res.writeHead(200);
  res.end('SocketServer Running.');
}

io.on('connection', function (socket) {
  // FIRST CONNECT
  // socket.emit('blogItems', { items: _items });

  // LOAD BLOG ITEMS
  socket.on('getBlogItems', function (data) {
    console.log("getBlogItems: "+data.limit+"/"+data.skip);
    getItems(data.skip, data.limit, function(error){
      socket.emit('getBlogItems', { 'items': null, 'error': error });
    },
    function(_items){
      socket.emit('getBlogItems', { 'items': _items });
    });
  });


  // ADD BLOG ITEM
  socket.on('addBlogItem', function (data) {
    console.log("addBlogItem: "+data.session+"/"+data.title);
    addItem(data.session, data.token, data.title, data.content, function(success, error){
      socket.emit('addBlogItem', { 'success':success, 'error':error });
    });
  });

  // DELETE BLOG ITEM
  socket.on('deleteBlogItem', function (data) {
    console.log("deleteBlogItem: "+data.session+"/"+data.id);
    deleteItem(data.session, data.token, data.id, function(success, error){
      socket.emit('deleteBlogItem', { 'success':success, 'error':error, 'id':data.id });
    });
  });

  // EDIT BLOG ITEM
  socket.on('editBlogItem', function (data) {
    console.log("editBlogItem: "+data.session+"/"+data.title);
    editItem(data.session, data.token, data.id, data.title, data.content, function(success, error){
      socket.emit('editBlogItem', { 'success':success, 'error':error, 'id':data.id });
    });
  });

});



// =======================================================
//  DATABASE - BLOG ITEMS
// =======================================================

function addItem(session, token, title, content, callback){
  checkToken(session, token, function(success, memberid){

    if(success){

      title = mysql.escape(title);
      content = mysql.escape(content);
      author = mysql.escape(memberid);
      mysql.query('INSERT INTO blog(author,title,content) VALUES ('+author+', '+title+', '+content+')',function(error,rows){
        if (error){
          callback(false, error);
        }else{
          callback(true, null);
        }
      });

    }else{
      callback(false, "Unauthorized");
    }

  });
}

function deleteItem(session, token, id, callback){
  checkToken(session, token, function(success, memberid){

    if(success){
      id = mysql.escape(id);
      mysql.query('DELETE FROM blog WHERE id='+id,function(error,rows){
        if (error){
          callback(false, error);
        }else{
          callback(true, null);
        }
      });

    }else{
      callback(false, "Unauthorized");
    }

  });
}

function editItem(session, token, id, title, content, callback){
  checkToken(session, token, function(success, memberid){

    if(success){

      id = mysql.escape(id);
      title = mysql.escape(title);
      content = mysql.escape(content);
      author = mysql.escape(memberid);
      mysql.query('UPDATE blog SET title='+title+',content='+content+' WHERE id='+id+" AND author="+author,function(error,rows){
        if (error){
          callback(false, error);
        }else{
          callback(true, null);
        }
      });

    }else{
      callback(false, "Unauthorized");
    }

  });
}


function getItems(skip, limit, err, callback){
  // Security
  if(!isNumber(skip) || !isNumber(limit)){callback(false);return;}
  skip = mysql.escape(skip);
  limit = mysql.escape(limit);

  // Real Stuff
  mysql.query('SELECT blog.id as "id", \
  blog.title as "title", \
  blog.content as "content", \
  blog.added as "added", \
  user.firstname as "[author.firstname]", \
  user.lastname as "[author.lastname]", \
  blog.author as "[author.id]" \
  FROM blog JOIN user ON blog.author=user.id \
  ORDER BY added DESC LIMIT '+limit+" OFFSET "+skip,function(error,rows){

    // console.log("ERROR: ", error);
    // console.log("Rows: ", rows);
    if (error){
      err(error)
    }else{
      var out = [];
      for(id in rows){
        item = rows[id];
        out.push(reStructItem(item));
      }
      callback(out);
    }

  });
}




// =======================================================
//  DATABASE - USER STUFF
// =======================================================

function checkToken(session, token, callback){
  // Security
  session = mysql.escape(session);
  token = mysql.escape(token);

  // Real Stuff
  mysql.query('SELECT `memberid` FROM `session` WHERE `session`='+session+' AND `token`='+token,function(error,rows){
    if (error || rows.length < 1){
      callback(false, -1);
    }else{
      callback(true, rows[0].memberid);
    }
  });
}


// =======================================================
//  HELPER
// =======================================================

function isNumber(obj) { return !isNaN(parseFloat(obj)) }

function reStructItem(item){
    var out = {};
    for(var index in item) {
      var value = item[index];
      if(matches = index.match(/^\[(.*)\.(.*)\]$/)){
        var key1 = matches[1];
        var key2 = matches[2];
        if (!(key1 in out)){out[key1] = {};}
        out[key1][key2] = value;
      }else{
        out[index] = value;
      }
    }
    return out;
}
