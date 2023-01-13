import React, {useContext, useEffect, useRef, useState} from 'react'
import ChatBar from './ChatBar'
import ChatBody from './ChatBody'
import ChatFooter from './ChatFooter'
import AuthContext from "../../contexts/auth-context";
import WsContext from "../../contexts/ws-context";


const ChatPage = () => {

    const authCtx = useContext(AuthContext);
    const socket = useContext(WsContext)

    const lastMessageRef = useRef(null);
    const [messages, setMessages] = useState([])
    const [typingStatus, setTypingStatus] = useState("")



    useEffect(() => {

        socket.onmessage = (data) => {
            data = JSON.parse(data.data)

            if (data.type != 'typing') {
                setMessages([...messages, data])
            } else {
                setTypingStatus(data.from + " is typing...")
                setTimeout(() => {
                    setTypingStatus("")
                }, 3000);
            }
        };
    }, [socket, messages])

    useEffect(() => {
        lastMessageRef.current?.scrollIntoView({behavior: 'smooth'});
    }, [messages]);

    return (
        <div className="chat">
            <ChatBar socket={socket}/>
            <div className='chat__main'>
                <ChatBody messages={messages} typingStatus={typingStatus} lastMessageRef={lastMessageRef}/>
                <ChatFooter socket={socket}/>
            </div>
        </div>
    )
}

export default ChatPage