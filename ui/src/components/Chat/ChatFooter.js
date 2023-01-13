import React, {useState} from 'react';
import uuid from 'react-uuid';

const ChatFooter = ({socket}) => {
    const [message, setMessage] = useState("")
    const handleTyping = () => socket.send(
        JSON.stringify({
            id: uuid(),
            type: 'typing',
            from: localStorage.getItem('userId'),
            to: 'change_me'
        }));

    const handleSendMessage = (e) => {
        e.preventDefault()
        if (message.trim() && localStorage.getItem('userId')) {
            socket.send(
                JSON.stringify({
                    id: uuid(),
                    type: 'message',
                    text: message,
                    from: localStorage.getItem('userId'),
                    to: 'change_me'
                }));
        }
        setMessage('');
    }
    return (
        <div className='chat__footer'>
            <form className='form' onSubmit={handleSendMessage}>
                <input
                    type="text"
                    placeholder='Write message'
                    className='message'
                    value={message}
                    onChange={e => setMessage(e.target.value)}
                    onKeyDown={handleTyping}
                />
                <button className="sendBtn">SEND</button>
            </form>
        </div>
    )
}

export default ChatFooter