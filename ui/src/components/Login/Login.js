import React, {useContext, useState} from 'react';
import {useNavigate} from 'react-router-dom';
import AuthContext from "../../contexts/auth-context";


import classes from './Login.module.css';

import jwt from 'jwt-decode'

import useInput from '../../hooks/use-input'


const Login = () => {
    const navigate = useNavigate();
    const authCtx = useContext(AuthContext);
    const [error, setError] = useState(null);


    const {
        value: email,
        isValid: enteredEmailIsValid,
        hasError: emailInputHasError,
        valueChangeHandler: emailChangeHandler,
        inputBlurHandler: emailBlurHandler,
    } = useInput(value => value.includes('@'));


    const {
        value: password,
        isValid: enteredPasswordIsValid,
        hasError: passwordInputHasError,
        valueChangeHandler: passwordChangeHandler,
        inputBlurHandler: passwordBlurHandler,
    } = useInput(value => value.trim() !== '');


    let formIsValid = false;

    if (enteredPasswordIsValid && enteredEmailIsValid) {
        formIsValid = true;
    }


    const submitHandler = async (event) => {
        event.preventDefault();
        if (!enteredPasswordIsValid && !enteredEmailIsValid) {
            return;
        }

        try {
            const response = await fetch('http://localhost:8080/v1/user/login', {
                method: 'POST',
                body: JSON.stringify({
                    email: email,
                    password: password
                }),
                headers: {
                    'Content-Type': 'application/json',
                }

            }).then((response) => {
                if (!response.ok) {
                    if (response.status === 401) {
                        if (response.statusText === 'Unauthorized') {
                            setError('Wrong email or password');
                        } else {
                            setError(response.statusText);
                        }
                    }
                } else {
                    return response.json();
                }
            }).then((response) => {
                if (response) {
                    const decodeToken = jwt(response.data.token);
                    const expirationTime = new Date(new Date().getTime() + 60 * 6000000);
                    authCtx.login(response.data.token, expirationTime.toISOString(), decodeToken.data);
                    navigate('/chat', {krasi:123})
                }
            }).catch((error) => {
                setError(error.statusText);
            });

        } catch (error) {
            setError(error.message);
        }
    }

    return (
        <form className="home__container" onSubmit={submitHandler}>
            <h2 className="home__header">Sign In</h2>
            <label htmlFor="email">Email</label>
            <input
                type="email"
                id="email"
                className="form__input"
                value={email}
                onChange={emailChangeHandler}
                onBlur={emailBlurHandler}
            />
            {emailInputHasError && <p className={classes['error-text']}>Email must not be empty.</p>}
            <label htmlFor="email">Password</label>
            <input
                type="password"
                id="password"
                className="form__input"
                value={password}
                onChange={passwordChangeHandler}
                onBlur={passwordBlurHandler}
            />
            {passwordInputHasError && <p className={classes['error-text']}>Password must not be empty.</p>}
            <button className="home__cta" disabled={!formIsValid}>SIGN IN</button>
            {error && <p className={`${classes['error-text']} ${classes.padding}`}>{error}</p>}
        </form>
    );
};

export default Login;