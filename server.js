
let webSocketServer = new (require('ws')).Server({port: (process.env.PORT || 3000)});
let dateTime = require('date-and-time')


    webSockets = {}
    console.log("Listening on port 3000...")

    // connect to database
    const mysql = require("mysql");
    const connectionDB = mysql.createConnection({
        host: "127.0.0.1" ,
        user: "root" ,
        password:"" ,
        database: "your_place",
    });
    connectionDB.connect(function (error){
        if(error)
        {     throw error;     }

        else
        {    console.log("connection with database done successfully");     }
    });

    webSocketServer.on('connection', function (webSocket,req) {

        let uri = req.url
        let userID = uri.substr(5, 1);

    webSockets[userID] = webSocket
    console.log('connected: ' + userID +uri + ' in ' + Object.getOwnPropertyNames(webSockets))

    webSocket.on('message', function(message) {

        let now  =  new Date();
        let date = dateTime.format(now,'YYYY-MM-DD HH:mm:ss');

        console.log('received from ' + userID + ': ' + message)
        let messageArray = JSON.parse(message)

        connectionDB.connect(function(err) {
            let sql = "INSERT INTO `chats` (`message`, `date` , `user_id`,`receiver_id`) VALUES ('"+messageArray['message']+"','"+date+"','"+userID+"','"+ messageArray['userId']+"')";
            connectionDB.query(sql, function (err, result) {
                console.log("1 record inserted");
            });
        })

        for(let myKey in messageArray) {

            console.log("key:"+myKey+", value:"+messageArray[myKey]);
        }
        console.log('send to ' + messageArray['userId'])
        let toUserWebSocket = webSockets[messageArray['userId']]
        if (toUserWebSocket) {

            console.log('sent to ' + messageArray['userId'] + ': ' + JSON.stringify(messageArray))
            toUserWebSocket.send(JSON.stringify(messageArray))

        }

    })

    webSocket.on('close', function () {
        delete webSockets[userID]
        console.log('dis connected: ' + userID)
    })


})
