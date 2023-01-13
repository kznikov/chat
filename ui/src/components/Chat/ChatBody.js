import React, {useContext} from 'react';
import AuthContext from "../../contexts/auth-context";


const ChatBody = ({messages, typingStatus, lastMessageRef}) => {

    const authCtx = useContext(AuthContext);

    const logoutHandler = () => {
        authCtx.logout();
    }

    return (
        <>
            <header className='chat__mainHeader'>
                <p>Chat</p>
                <button className='leaveChat__btn' onClick={logoutHandler}>Log Out</button>
            </header>


            <div className='message__container'>
                {messages.map(message => (
                    message.from === localStorage.getItem("userName") ? (
                        <div className="message__chats" key={message.id}>
                            <p className='sender__name'>You</p>
                            <div className='message__sender'>
                                <p>{message.text}</p>
                            </div>
                        </div>
                    ) : (
                        <div className="message__chats" key={message.id}>
                            <p>{message.to}</p>
                            <div className='message__recipient'>
                                <p>{message.text}</p>
                            </div>
                        </div>
                    )
                ))}

                <div className='message__status'>
                    <p>{typingStatus}</p>
                </div>
                <div ref={lastMessageRef}/>
            </div>
        </>
    )
}

export default ChatBody