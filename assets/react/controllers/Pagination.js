import React from 'react';

const PaginationSection = ({ itemsPerPage, totalItems, paginateValue ,currentPage }) => {
    const pageNumbers = [];
    for (let i = 1; i <= Math.ceil(totalItems / itemsPerPage); i++) {
        pageNumbers.push(i);
    }
   
    return (
      
        <nav>
            <ul className="pagination">
                <li className={`page_item $(curentPage)===1 ? 'disabled' : ''}`}> 
                <a onClick={() => paginate(currentPage - 1)} href="#" className="page-link">
            Previous
          </a>
                </li>
                {pageNumbers.map(number => (
                    <li key={number} className={`page-item ${currentPage === number ? 'active' : ''}`}>
                        <a onClick={() => paginateValue(number)} href="#" className="page-link">
                            {number}
                        </a>
                    </li>
                ))}
                 <li className={`page-item ${currentPage === pageNumbers.length ? 'disabled' : ''}`}>
          <a onClick={() => paginate(currentPage + 1)} href="#" className="page-link">
            Next
          </a>
        </li>
            </ul>
        </nav>
    );
};

export default PaginationSection;