import {Route, Routes, Navigate} from 'react-router-dom';
import "bootstrap/dist/css/bootstrap.min.css"

import Login from "./components/Login/Login";
import ChatPage from "./components/Chat/ChatPage";
import AuthContext from "./contexts/auth-context";
import {useContext} from "react";

function App() {

    const authCtx = useContext(AuthContext);

    return (
        <div>
            <Routes>
                {!authCtx.isLoggedIn && <Route path="/" element={<Login/>}/>}
                {authCtx.isLoggedIn && <Route path="/chat" element={<ChatPage/>}/>}
                {authCtx.isLoggedIn && <Route path="/" element={<ChatPage/>}/>}
                {/*<Route path="*" element={<NotFound/>} />*/}
                <Route path="*" element={<Navigate to="/" />} />
            </Routes>
        </div>
    );
}

export default App;