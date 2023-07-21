import {configureStore} from "@reduxjs/toolkit"
import {setupListeners} from "@reduxjs/toolkit/query"
import authReducer from "./features/auth/authSlice"
import {authApi} from "./services/auth"
import {categories} from "./services/category"

const store = configureStore({
    reducer: {
      auth: authReducer,
      [authApi.reducerPath]: authApi.reducer,
      [categories.reducerPath]: categories.reducer
    },
    middleware: (getDefaultMiddleware) => getDefaultMiddleware().concat([
      authApi.middleware,
      categories.middleware
    ])
})

setupListeners(store.dispatch)

export default store
