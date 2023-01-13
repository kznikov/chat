import React from 'react';
import ReactDOM from 'react-dom/client';
import {BrowserRouter} from 'react-router-dom';
import './index.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import {AuthContextProvider} from "./contexts/auth-context";
import App from './App';
import {WsContextProvider} from "./contexts/ws-context";

const root = ReactDOM.createRoot(document.getElementById('root'));

root.render(
    <AuthContextProvider>
        <WsContextProvider>
            <BrowserRouter>
                <App />
            </BrowserRouter>
        </WsContextProvider>
    </AuthContextProvider>
);

