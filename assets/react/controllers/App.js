import React, { useEffect, useState } from 'react';
import axios from 'axios';
import PaginationSection from './Pagination'; // Import the Pagination component

const ApplicationTable = () => {

    const [applications, setApplications] = useState([]);
    
    const [loading, setLoading] = useState(true);
    
    const [error, setError] = useState(null);
    
    const [searchQuery, setSearchQuery] = useState('');
    
    const [currentPage, setCurrentPage] = useState(1);
    
    const itemsPerPage = 10; //Page Limits Values
    //Sorting constant default Sort on applicationNO as ascending
    const [sortConfig, setSortConfig] = useState({ key: 'applicationNO', direction: 'ascending' });
    //ApiUrl
    const apiUrl ="https://raw.githubusercontent.com/RashitKhamidullin/Educhain-Assignment/refs/heads/main/applications";
    
    useEffect(() => {
        const fetchApplications = async () => {
            try {
                const response = await axios.get(apiUrl);
                setApplications(response.data);
            } catch (err) {
                setError('Failed to fetch applications');
            } finally {
                setLoading(false);
            }
        };

        fetchApplications();
    }, []);

   // Filter applications based on search query 
   const filteredData = applications.filter(value =>
    value.applicantName.toLowerCase().includes(searchQuery.toLowerCase()) ||
    value.status_En.toLowerCase().includes(searchQuery.toLowerCase()) ||
    value.studentID.toString().includes(searchQuery)
    );
    //Sort The header value as define column
    const sorteHeader = [...filteredData].sort((a, b) => {
        if (a[sortConfig.key] < b[sortConfig.key]) {
          return sortConfig.direction === 'ascending' ? -1 : 1;
        }
        if (a[sortConfig.key] > b[sortConfig.key]) {
          return sortConfig.direction === 'ascending' ? 1 : -1;
        }
        return 0;
      });
      // Pagination value
    const indexOfLastData = currentPage * itemsPerPage;
    const indexOfFirstData = indexOfLastData - itemsPerPage;
    const currentData = sorteHeader.slice(indexOfFirstData, indexOfLastData);

    const handleSearchChange = (e) => {
        setSearchQuery(e.target.value);
    };

    const PageChangeAction = (pageNumber) => {
        setCurrentPage(pageNumber);
    };

    const requestSort = (key) => {
        let direction = 'ascending';
        if (sortConfig.key === key && sortConfig.direction === 'ascending') {
            direction = 'descending';
        }
        setSortConfig({ key, direction });
    };

   

    return (
        <div className="container">
             <input
                type="text"
                className="form-control mb-5 input-lg flex-fill"
                placeholder="Search by Applicant Name, Status, or Student ID..."
                value={searchQuery}
                onChange={handleSearchChange}
            />
            {loading ? (
                <p>Loading...</p>
            ) : error ? (
                <p>{error}</p>
            ) : (
                <div className="table-responsive">
                    <table className="table table-bordered table-hover">
                        <thead className="thead-light">
                        <tr>
                                <th onClick={() => requestSort('applicationNO')} style={{ cursor: 'pointer' }}>Application No</th>
                                <th onClick={() => requestSort('applicantName')} style={{ cursor: 'pointer' }}>Applicant Name</th>
                                <th onClick={() => requestSort('applicationDate')} style={{ cursor: 'pointer' }}>Application Date</th>
                                <th onClick={() => requestSort('studentID')} style={{ cursor: 'pointer' }}>Student ID</th>
                                <th onClick={() => requestSort('paidAmount')} style={{ cursor: 'pointer' }}>Paid Amount</th>
                                <th onClick={() => requestSort('status_En')} style={{ cursor: 'pointer' }}>Status (English)</th>
                                <th onClick={() => requestSort('status_Ar')} style={{ cursor: 'pointer' }}>Status (Arabic)</th>

                            </tr>
                        </thead>
                        <tbody>
                            {currentData.map((valueOf, index) => (
                                <tr key={index}>
                                    <td>{valueOf.applicationNO}</td>
                                    <td>{valueOf.applicantName}</td>
                                    <td>{valueOf.applicationDate}</td>
                                    <td>{valueOf.studentID}</td>
                                    <td>{valueOf.paidAmount}</td>
                                    <td>{valueOf.status_En}</td>
                                    <td>{valueOf.status_Ar}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}
            <PaginationSection
                itemsPerPage={itemsPerPage}
                totalItems={filteredData.length}
                paginateValue={PageChangeAction}
                currentPage={currentPage}
            />
        </div>
    );
};

export default ApplicationTable;