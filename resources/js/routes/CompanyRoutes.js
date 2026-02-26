const baseUrl = document.querySelector('meta[name="url"]').content;
let companyRoutes = {
    index : `${baseUrl}/companies/index`,
    form :(company_id = null) =>
    {
        if(company_id)
        {
            return `${baseUrl}/companies/form/${company_id}`;
        }
        return `${baseUrl}/companies/form`;
    },
    createOrUpdate: (company_id) => {
        if(company_id > 0)
        {
            return `${baseUrl}/companies/create-or-update/${company_id}`;
        }
        return `${baseUrl}/companies/create-or-update`;
    },
    getAllData : `${baseUrl}/companies/get-data`,
    delete :(company_id) =>
    {
        return `${baseUrl}/companies/delete/${company_id}`;
    }
}

export {companyRoutes};