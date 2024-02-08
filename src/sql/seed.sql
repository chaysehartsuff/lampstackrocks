-- Seed users
INSERT INTO users (first_name, last_name) VALUES
('John', 'Doe'),
('Jane', 'Doe'),
('Alice', 'Johnson'),
('Bob', 'Smith'),
('Carol', 'Williams'),
('David', 'Brown'),
('Eve', 'Davis'),
('Frank', 'Miller'),
('Grace', 'Wilson'),
('Henry', 'Moore');


-- Seed Addresses
INSERT INTO addresses (address, user_id) VALUES
('123 Elm Street, Springfield', 1),
('456 Maple Avenue, Springfield', 2),
('789 Oak Street, Springfield', 3),
('101 Pine Lane, Springfield', 4),
('202 Birch Road, Springfield', 5),
('303 Cedar Blvd, Springfield', 6),
('404 Spruce St, Springfield', 7),
('505 Aspen Way, Springfield', 8),
('606 Walnut Street, Springfield', 9),
('707 Cherry Lane, Springfield', 10);

-- Seed Phones
INSERT INTO phones (phone, user_id) VALUES
('555-1234', 1),
('555-5678', 2),
('555-9012', 3),
('555-3456', 4),
('555-7890', 5),
('555-2345', 6),
('555-6789', 7),
('555-3457', 8),
('555-4567', 9),
('555-5679', 10);

-- Seed emails
INSERT INTO emails (email, user_id) VALUES
('john.doe@example.com', 1),
('jane.doe@example.com', 2),
('alice.johnson@example.com', 3),
('bob.smith@example.com', 4),
('carol.williams@example.com', 5),
('david.brown@example.com', 6),
('eve.davis@example.com', 7),
('frank.miller@example.com', 8),
('grace.wilson@example.com', 9),
('henry.moore@example.com', 10);
