import {useParams} from "react-router-dom";
import {useGetArticlesByCategoryQuery} from "../../../services/category";

export const Category = () => {

  const {category} = useParams()
  const {data, isLoading} = useGetArticlesByCategoryQuery({category, page: 1,q: null})

  console.log(data)

  // import {} = useGetCategoryQuery()
  return <>Category</>
}
