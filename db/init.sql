CREATE TABLE IF NOT EXISTS portfolios (
    id INT PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS allocations (
    id INT PRIMARY KEY,
    portfolio_id INT,
    shares INT,
    FOREIGN KEY (portfolio_id) REFERENCES portfolios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY,
    portfolio_id INT,
    allocation_id INT,
    type VARCHAR(255),
    shares INT,
    FOREIGN KEY (portfolio_id) REFERENCES portfolios(id) ON DELETE CASCADE,
    FOREIGN KEY (allocation_id) REFERENCES allocations(id) ON DELETE CASCADE
);
