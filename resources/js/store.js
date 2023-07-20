import {configureStore} from "@reduxjs/toolkit"
import {setupListeners} from "@reduxjs/toolkit/query"
import authReducer from "./features/auth/authSlice"
import {authApi} from "./services/auth"

const store = configureStore({
    reducer: {
        auth: authReducer,
        [authApi.reducerPath]: authApi.reducer
    },
    middleware: (getDefaultMiddleware) => getDefaultMiddleware().concat([
	        authApi.middleware
    ])
})

setupListeners(store.dispatch)

export default store
