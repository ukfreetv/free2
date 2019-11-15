# free2 - Welcome to the sample code 

Most of the things you want to see are in the free2/registrationDemo folder.

The database connection config is in free2/siteconfig.php


# Notes

- This isn't a complete version of what was asked for, but what was doable in a couple of hours.

- The start route is http://localhost:5678/demo/demo

- The system doesn't use postcodes.io, but a demo version of the full PAF.  The docs for that are at
  https://www.poweredbypaf.com/wp-content/uploads/2017/07/Latest-Programmers_guide_Edition-7-Version-6.pdf
  
- The demo data supports only "YO" and "PL" postcodes, so YO14 0NE works, for example.   

- The code in the pseph/nff folders is a lightweight framework ("no framework, framework") of my own devising.  


# Database table defintions

CREATE TABLE `tblPAF` (
  `intID` int(11) NOT NULL,
  `Postcode` varchar(8) DEFAULT NULL,
  `Post Town` text,
  `Dependent Locality` varchar(30) DEFAULT NULL,
  `Double Dependent Locality` text,
  `Thoroughfare & Descriptor` varchar(38) DEFAULT NULL,
  `Dependent Thoroughfare & Descriptor` varchar(38) DEFAULT NULL,
  `Building Number` varchar(45) DEFAULT NULL,
  `Building Name` varchar(45) DEFAULT NULL,
  `Sub Building Name` varchar(30) DEFAULT NULL,
  `PO Box` text,
  `Department Name` text,
  `Organisation Name` varchar(60) DEFAULT NULL,
  `UDPRN` int(11) DEFAULT NULL,
  `Postcode Type` varchar(1) DEFAULT NULL,
  `SU Organisation Indicator` varchar(1) DEFAULT NULL,
  `Delivery Point Suffix` text,
  `Unknown1` int(8) DEFAULT NULL,
  `Unknown2` mediumint(7) DEFAULT NULL,
  `Unknown3` smallint(3) DEFAULT NULL,
  `Unknown4` mediumint(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tblPAF`
  ADD PRIMARY KEY (`intID`),
  ADD KEY `Postcode` (`Postcode`(7)),
  ADD KEY `Dependent Locality` (`Dependent Locality`),
  ADD KEY `Thoroughfare & Descriptor` (`Thoroughfare & Descriptor`),
  ADD KEY `Unknown4` (`Unknown4`),
  ADD KEY `Unknown3` (`Unknown3`),
  ADD KEY `Unknown2` (`Unknown2`),
  ADD KEY `Unknown1` (`Unknown1`),
  ADD KEY `SU Organisation Indicator` (`SU Organisation Indicator`),
  ADD KEY `Postcode Type` (`Postcode Type`),
  ADD KEY `UDPRN` (`UDPRN`),
  ADD KEY `Organisation Name` (`Organisation Name`);

ALTER TABLE `tblPAF`
  MODIFY `intID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=553240;
COMMIT;

CREATE TABLE `tblUserdata` (
  `intId` int(11) NOT NULL,
  `strSalutation` varchar(6) NOT NULL,
  `strGender` varchar(1) NOT NULL,
  `dtBirthdate` date NOT NULL,
  `strFirstname` varchar(31) NOT NULL,
  `strSurname` varchar(31) NOT NULL,
  `strEmailAddress` varchar(63) NOT NULL,
  `strPasswordHash` varchar(63) NOT NULL,
  `strPostcode` varchar(8) NOT NULL,
  `strHouseNumber:` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



ALTER TABLE `tblUserdata`
  ADD PRIMARY KEY (`intId`),
  ADD UNIQUE KEY `strEmailAddress` (`strEmailAddress`),
  ADD KEY `strSalutation` (`strSalutation`),
  ADD KEY `strGender` (`strGender`),
  ADD KEY `dtBirthdate` (`dtBirthdate`),
  ADD KEY `strFirsname` (`strFirstname`),
  ADD KEY `strSurname` (`strSurname`),
  ADD KEY `strPasswordHash` (`strPasswordHash`);

Brian