import React, { useState  , useEffect } from 'react';


const WsContext = React.createContext({
    socket: ''
});

export const WsContextProvider = (props) => {
    const [socket, setSocket] = useState(null);

    useEffect(() => {

        const socket = new WebSocket('ws://localhost:9502')

        socket.onopen = () => {
            setSocket(socket);
        };

        socket.onclose = () => {
            setSocket(null);
        };

        return () => {
            socket.close();
        };
    }, []);

    return (
        <WsContext.Provider value={socket}>
            {props.children}
        </WsContext.Provider>
    );
};

export default WsContext;
