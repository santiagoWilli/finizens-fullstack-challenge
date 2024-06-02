CREATE TABLE IF NOT EXISTS portfolios (
    id INT PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS allocations (
    id INT PRIMARY KEY,
    portfolio_id INT,
    shares INT,
    FOREIGN KEY (portfolio_id) REFERENCES portfolios(id) ON DELETE CASCADE
);
