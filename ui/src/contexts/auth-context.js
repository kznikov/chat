import React, { useState  , useEffect , useCallback } from 'react';


let logoutTimer;

const AuthContext = React.createContext({
    token: '',
    isLoggedIn: false,
    userData:'',
    login: (token) => {},
    logout: () => {}
});

const calculateRemainingTime = (expirationTime) => {
    const currentTime = new Date().getTime();
    const adjExpirationTime = new Date(expirationTime).getTime();
    const remainingDuration = adjExpirationTime - currentTime;

    return remainingDuration;
}
const retrieveStoredToken = () => {

    const storedToken = localStorage.getItem('token');
    const storedExpirationDate = localStorage.getItem('expirationTime');
    const userData = JSON.parse(localStorage.getItem('userData'));

    const remainingTime = calculateRemainingTime(storedExpirationDate);

    if(remainingTime <= 3600){
        localStorage.clear();
        return null;
    }
    return {
        storedToken,
        duration:remainingTime,
        userData
    };
}

export const AuthContextProvider = (props) => {
    const tokenData = retrieveStoredToken();

    let initialToken;
    let initialUserData;

    if(tokenData){
        initialToken = tokenData.storedToken;
        initialUserData = tokenData.userData;
    }

    const [token, setToken] = useState(initialToken);
    const [userData, setUserData] = useState(initialUserData);

    const userIsLoggedIn = !!token;

    const logoutHandler = useCallback(() => {
        setToken(null);
        localStorage.clear();
        if(logoutTimer){
            clearTimeout(logoutTimer);
        }
    }, []);

    const loginHandler = (token, expirationTime, userData) => {
        setToken(token);
        setUserData(userData);
        localStorage.setItem('token' , token);
        localStorage.setItem('expirationTime' , expirationTime);
        localStorage.setItem('userData', JSON.stringify(userData));

        const remainingTime =  calculateRemainingTime(expirationTime);
        logoutTimer = setTimeout(logoutHandler, remainingTime);
    };

    useEffect(() => {
        if(tokenData) {
            logoutTimer = setTimeout(logoutHandler, tokenData.duration);
        }
    },[tokenData , logoutHandler]);

    const contextValue = {
        token: token,
        isLoggedIn: userIsLoggedIn,
        login: loginHandler,
        logout: logoutHandler,
        userData:userData,
    };

    return (
        <AuthContext.Provider value={contextValue}>
            {props.children}
        </AuthContext.Provider>
    );
};

export default AuthContext;
