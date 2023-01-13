import {useState, useCallback, useContext} from 'react';
import AuthContext from "../store/auth-context";
import {useHistory} from "react-router-dom";

const useHttp = () => {
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState(null);
    const authCtx = useContext(AuthContext);
    const history = useHistory();

    const sendRequest = useCallback(async (requestConfig, applyData) => {
        setIsLoading(true);
        setError(null);
        let token = authCtx.token;
        if(authCtx.token.storedToken){
            token = authCtx.token.storedToken;
        }

        try {
            const response = await fetch(requestConfig.url, {
                method: requestConfig.method ? requestConfig.method : 'GET',
                headers: requestConfig.headers ? requestConfig.headers : {
                    'Content-Type':  'application/json',
                    Authorization: `Bearer ${token}`
                },
                body: requestConfig.body ? JSON.stringify(requestConfig.body) : null,
            });
            
            if (!response.ok) {
                if(response.status === 401){
                    if(response.statusText === "Unauthorized"){
                        authCtx.logout();
                        history.push('/Login');
                    }
                    setError(response.statusText);
                }else if (response.status === 404 && response.statusText === 'Not Found'){
                    const data  = [];
                    applyData(data);

                }

            }else{
                const data = await response.json();
                applyData(data);
            }


        } catch (err) {
            setError(err.message || 'Something went wrong!');
        }
        setIsLoading(false);
    }, []);
    return {
        isLoading,
        error,
        sendRequest,
    };
};

export default useHttp;

