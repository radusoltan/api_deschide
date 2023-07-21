import {createApi, fetchBaseQuery} from "@reduxjs/toolkit/query/react"
import i18n from "../i18n";

const baseUrl = process.env.MIX_APP_URL + '/api'
const headers = {
  Authorization: `Bearer ${localStorage.getItem('userToken')}`
}

const createRequest = url => ({url, headers})

export const categories = createApi({
  reducerPath: 'categories',
  baseQuery: fetchBaseQuery({baseUrl}),
  tagTypes: ['Categories',"Articles"],
  endpoints: builder => ({
    getCategories: builder.query({
      query: page => createRequest(`/categories?page=${page}&locale=${i18n.language}`),
      providesTags: result => result ? [
        ...result.data.map(({id})=> ({type: 'Categories', id})),
        {type: 'Categories', id: 'PARTIAL-LIST'}
      ] : [{type: 'Categories', id: 'PARTIAL-LIST'}]
    }),
    getCategory: builder.query({
      query: id => createRequest(`/categories/${id}?locale=${i18n.language}`),
    }),
    getArticlesByCategory: builder.query({
      query: ({category, page, q}) => {
        const term = q === null ? '' : q
        return {
          url: `/category/${category}/articles`,
          headers,
          params: {
            page,
            term,
            locale: i18n.language
          }
        }
      }
    }),
    addCategory: builder.mutation({
      query: body => ({
      	url: `/categories`,
        method: 'POST',
        body,
        headers
      }),
      invalidatesTags: [{type: 'Categories', id: 'PARTIAL-LIST'}]
    }),
    updateCategory: builder.mutation({
      query: ({id, body}) => ({
        url: `/categories/${id}?locale=${i18n.language}`,
        method: 'PATCH',
        body,
        headers
      }),
      invalidatesTags: (resp, error, {id}) => [{type: 'Categories', id}]
    }),
    deleteCategory: builder.mutation({
      query: id => ({
        url: `/categories/${id}`,
        method: 'DELETE',
        headers
      }),
      invalidatesTags: (resp, error, id) => [{type: 'Categories', id}]
    })
  })
})

export const {
  useGetCategoriesQuery,
  useGetCategoryQuery,
  useGetArticlesByCategoryQuery,
  useUpdateCategoryMutation,
  useDeleteCategoryMutation,
  useAddCategoryMutation
} = categories
