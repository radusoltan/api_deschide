import {createApi, fetchBaseQuery} from "@reduxjs/toolkit/query/react"

const baseUrl = process.env.MIX_BACKEND_URL

export const authApi = createApi({
  reducerPath: "authApi",
  baseQuery: fetchBaseQuery({
    baseUrl: baseUrl,
  }),
  endpoints: build => ({
    getUserDetails: build.query({
      query: () => `/api/user`,
    })
  })
})

export const {useGetUserDetailsQuery} = authApi
