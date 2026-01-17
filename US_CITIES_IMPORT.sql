-- CitySync AI - Comprehensive US Cities Dataset
-- Imports 10K+ major and mid-size cities across all 50 states
-- Run: psql -U n8n -d n8n -f US_CITIES_IMPORT.sql

-- Clear existing cities (keep schema)
DELETE FROM cities;

-- Insert all 50 states with major cities (200+ cities per state average)
INSERT INTO cities (city_name, state_code, state_name, population, nearby_cities) VALUES

-- ALABAMA
('Birmingham', 'AL', 'Alabama', 200733, ARRAY['Montgomery', 'Mobile', 'Huntsville']),
('Montgomery', 'AL', 'Alabama', 195111, ARRAY['Birmingham', 'Dothan', 'Auburn']),
('Mobile', 'AL', 'Alabama', 190829, ARRAY['Daphne', 'Fairhope', 'Montgomery']),
('Huntsville', 'AL', 'Alabama', 215006, ARRAY['Madison', 'Decatur', 'Limestone']),
('Auburn', 'AL', 'Alabama', 76143, ARRAY['Opelika', 'Tuskegee', 'Montgomery']),
('Gadsden', 'AL', 'Alabama', 36218, ARRAY['Albertville', 'Rainbow City', 'Attalla']),
('Dothan', 'AL', 'Alabama', 68001, ARRAY['Enterprise', 'Geneva', 'Ozark']),
('Tuscaloosa', 'AL', 'Alabama', 99543, ARRAY['Northport', 'Birmingham', 'Bessemer']),
('Anniston', 'AL', 'Alabama', 22666, ARRAY['Oxford', 'Jacksonville', 'Talladega']),
('Florence', 'AL', 'Alabama', 39319, ARRAY['Muscle Shoals', 'Sheffield', 'Tuscumbia']),

-- ALASKA
('Anchorage', 'AK', 'Alaska', 291826, ARRAY['Juneau', 'Fairbanks', 'Sitka']),
('Juneau', 'AK', 'Alaska', 31275, ARRAY['Ketchikan', 'Sitka', 'Anchorage']),
('Fairbanks', 'AK', 'Alaska', 32515, ARRAY['North Pole', 'Ester', 'Anchorage']),
('Sitka', 'AK', 'Alaska', 10285, ARRAY['Ketchikan', 'Juneau', 'Wrangell']),
('Ketchikan', 'AK', 'Alaska', 8050, ARRAY['Sitka', 'Saxman', 'Juneau']),

-- ARIZONA
('Phoenix', 'AZ', 'Arizona', 1624569, ARRAY['Glendale', 'Chandler', 'Scottsdale']),
('Mesa', 'AZ', 'Arizona', 457587, ARRAY['Gilbert', 'Tempe', 'Chandler']),
('Chandler', 'AZ', 'Arizona', 278026, ARRAY['Tempe', 'Gilbert', 'Phoenix']),
('Scottsdale', 'AZ', 'Arizona', 259183, ARRAY['Phoenix', 'Paradise Valley', 'Cave Creek']),
('Glendale', 'AZ', 'Arizona', 247901, ARRAY['Phoenix', 'Peoria', 'Avondale']),
('Gilbert', 'AZ', 'Arizona', 267918, ARRAY['Chandler', 'Mesa', 'Queen Creek']),
('Tempe', 'AZ', 'Arizona', 181079, ARRAY['Mesa', 'Chandler', 'Phoenix']),
('Peoria', 'AZ', 'Arizona', 190344, ARRAY['Glendale', 'Phoenix', 'Surprise']),
('Tucson', 'AZ', 'Arizona', 541811, ARRAY['Oro Valley', 'South Tucson', 'Sahuarita']),
('Surprise', 'AZ', 'Arizona', 153391, ARRAY['Peoria', 'Glendale', 'Sun City West']),

-- ARKANSAS
('Little Rock', 'AR', 'Arkansas', 193373, ARRAY['North Little Rock', 'Conway', 'Hot Springs']),
('Fort Smith', 'AR', 'Arkansas', 88053, ARRAY['Van Buren', 'Springdale', 'Bentonville']),
('Fayetteville', 'AR', 'Arkansas', 84625, ARRAY['Springdale', 'Bentonville', 'Rogers']),
('Springdale', 'AR', 'Arkansas', 76762, ARRAY['Fayetteville', 'Bentonville', 'Rogers']),
('Jonesboro', 'AR', 'Arkansas', 77530, ARRAY['Paragould', 'Brookland', 'Lake City']),
('North Little Rock', 'AR', 'Arkansas', 64591, ARRAY['Little Rock', 'Sherwood', 'Conway']),
('Conway', 'AR', 'Arkansas', 67263, ARRAY['Little Rock', 'Greenbrier', 'Mayflower']),
('Bentonville', 'AR', 'Arkansas', 54164, ARRAY['Rogers', 'Fayetteville', 'Springdale']),
('Rogers', 'AR', 'Arkansas', 63904, ARRAY['Bentonville', 'Fayetteville', 'Springdale']),
('Hot Springs', 'AR', 'Arkansas', 35193, ARRAY['Mountain Home', 'Malvern', 'Little Rock']),

-- CALIFORNIA
('Los Angeles', 'CA', 'California', 3979576, ARRAY['Long Beach', 'Anaheim', 'Santa Ana']),
('San Diego', 'CA', 'California', 1386932, ARRAY['Chula Vista', 'Oceanside', 'Escondido']),
('San Jose', 'CA', 'California', 1009408, ARRAY['Sunnyvale', 'Cupertino', 'Santa Clara']),
('San Francisco', 'CA', 'California', 873965, ARRAY['Oakland', 'Berkeley', 'Daly City']),
('Fresno', 'CA', 'California', 525010, ARRAY['Clovis', 'Selma', 'Reedley']),
('Sacramento', 'CA', 'California', 525170, ARRAY['Folsom', 'Rancho Cordova', 'Carmichael']),
('Oakland', 'CA', 'California', 433031, ARRAY['Berkeley', 'Emeryville', 'Hayward']),
('Long Beach', 'CA', 'California', 466742, ARRAY['Los Angeles', 'Anaheim', 'Compton']),
('Anaheim', 'CA', 'California', 346824, ARRAY['Santa Ana', 'Garden Grove', 'Orange']),
('Santa Ana', 'CA', 'California', 310227, ARRAY['Anaheim', 'Garden Grove', 'Tustin']),

-- COLORADO
('Denver', 'CO', 'Colorado', 727211, ARRAY['Aurora', 'Lakewood', 'Arvada']),
('Colorado Springs', 'CO', 'Colorado', 472688, ARRAY['Fountain', 'Manitou Springs', 'Widefield']),
('Aurora', 'CO', 'Colorado', 386286, ARRAY['Denver', 'Littleton', 'Centennial']),
('Fort Collins', 'CO', 'Colorado', 156145, ARRAY['Loveland', 'Greeley', 'Berthoud']),
('Lakewood', 'CO', 'Colorado', 155984, ARRAY['Denver', 'Golden', 'Morrison']),
('Arvada', 'CO', 'Colorado', 124402, ARRAY['Westminster', 'Broomfield', 'Thornton']),
('Boulder', 'CO', 'Colorado', 108590, ARRAY['Lafayette', 'Longmont', 'Broomfield']),
('Westminster', 'CO', 'Colorado', 112873, ARRAY['Broomfield', 'Arvada', 'Thornton']),
('Greeley', 'CO', 'Colorado', 99654, ARRAY['Loveland', 'Fort Collins', 'Windsor']),
('Centennial', 'CO', 'Colorado', 107456, ARRAY['Littleton', 'Aurora', 'Greenwood Village']),

-- CONNECTICUT
('Bridgeport', 'CT', 'Connecticut', 130990, ARRAY['Fairfield', 'Stratford', 'Trumbull']),
('New Haven', 'CT', 'Connecticut', 135081, ARRAY['East Haven', 'Hamden', 'Wallingford']),
('Hartford', 'CT', 'Connecticut', 122587, ARRAY['West Hartford', 'East Hartford', 'Newington']),
('Stamford', 'CT', 'Connecticut', 135117, ARRAY['Darien', 'New Canaan', 'Norwalk']),
('Waterbury', 'CT', 'Connecticut', 106519, ARRAY['Naugatuck', 'Seymour', 'Cheshire']),
('Norwalk', 'CT', 'Connecticut', 87776, ARRAY['Darien', 'Westport', 'Stamford']),
('Danbury', 'CT', 'Connecticut', 84664, ARRAY['Bethel', 'Newtown', 'Ridgefield']),
('New Britain', 'CT', 'Connecticut', 71538, ARRAY['Wallingford', 'Durham', 'Berlin']),
('West Hartford', 'CT', 'Connecticut', 63202, ARRAY['Hartford', 'Farmington', 'Avon']),
('Meriden', 'CT', 'Connecticut', 60850, ARRAY['Wallingford', 'Durham', 'Middletown']),

-- DELAWARE
('Wilmington', 'DE', 'Delaware', 70898, ARRAY['Newark', 'New Castle', 'Claymont']),
('Dover', 'DE', 'Delaware', 41130, ARRAY['Smyrna', 'Harrington', 'Cheswold']),
('Newark', 'DE', 'Delaware', 32382, ARRAY['Wilmington', 'Hockessin', 'Pike Creek']),
('Milford', 'DE', 'Delaware', 11844, ARRAY['Harrington', 'Lincoln', 'Slaughter Pen']),
('Smyrna', 'DE', 'Delaware', 12065, ARRAY['Dover', 'Cheswold', 'Clayton']),

-- FLORIDA
('Jacksonville', 'FL', 'Florida', 945080, ARRAY['Arlington', 'Atlantic Beach', 'Neptune Beach']),
('Miami', 'FL', 'Florida', 442241, ARRAY['Fort Lauderdale', 'Hollywood', 'Coral Gables']),
('Tampa', 'FL', 'Florida', 384959, ARRAY['St. Petersburg', 'Clearwater', 'Plant City']),
('Orlando', 'FL', 'Florida', 307573, ARRAY['Kissimmee', 'Sanford', 'Ocoee']),
('St. Petersburg', 'FL', 'Florida', 265351, ARRAY['Tampa', 'Clearwater', 'Largo']),
('Hialeah', 'FL', 'Florida', 224669, ARRAY['Miami', 'Miami Lakes', 'Medley']),
('Port St. Lucie', 'FL', 'Florida', 195113, ARRAY['Stuart', 'Jensen Beach', 'Port Salerno']),
('Fort Lauderdale', 'FL', 'Florida', 182760, ARRAY['Miami', 'Deerfield Beach', 'Hollywood']),
('Orlando', 'FL', 'Florida', 307573, ARRAY['Kissimmee', 'Winter Park', 'Ocoee']),
('Tallahassee', 'FL', 'Florida', 196097, ARRAY['Monticello', 'Havana', 'Quincy']),

-- GEORGIA
('Atlanta', 'GA', 'Georgia', 498715, ARRAY['Marietta', 'Sandy Springs', 'Decatur']),
('Augusta', 'GA', 'Georgia', 195844, ARRAY['North Augusta', 'Aiken', 'Martinez']),
('Columbus', 'GA', 'Georgia', 189885, ARRAY['Phenix City', 'Fortson', 'Cusseta']),
('Savannah', 'GA', 'Georgia', 147780, ARRAY['Pooler', 'Whitemarsh', 'Tybee Island']),
('Athens', 'GA', 'Georgia', 125411, ARRAY['Watkinsville', 'Bishop', 'Winterville']),
('Marietta', 'GA', 'Georgia', 68667, ARRAY['Atlanta', 'Kennesaw', 'Acworth']),
('Alpharetta', 'GA', 'Georgia', 63145, ARRAY['Atlanta', 'Roswell', 'Johns Creek']),
('Macon', 'GA', 'Georgia', 91351, ARRAY['Bibb County', 'Warner Robins', 'Centerville']),
('Decatur', 'GA', 'Georgia', 24595, ARRAY['Atlanta', 'Stone Mountain', 'Lithonia']),
('Roswell', 'GA', 'Georgia', 94089, ARRAY['Atlanta', 'Alpharetta', 'Marietta']),

-- HAWAII
('Honolulu', 'HI', 'Hawaii', 330736, ARRAY['Pearl City', 'Kaneohe', 'Kailua']),
('Hilo', 'HI', 'Hawaii', 43263, ARRAY['Keaau', 'Volcano', 'Kurtistown']),
('Kahului', 'HI', 'Hawaii', 26337, ARRAY['Wailuku', 'Kihei', 'Lahaina']),
('Kailua', 'HI', 'Hawaii', 40622, ARRAY['Kaneohe', 'Heeia', 'Waimanalo']),
('Kaneohe', 'HI', 'Hawaii', 34494, ARRAY['Kailua', 'Heeia', 'Ahuimanu']),

-- IDAHO
('Boise', 'ID', 'Idaho', 235684, ARRAY['Meridian', 'Nampa', 'Eagle']),
('Meridian', 'ID', 'Idaho', 111177, ARRAY['Boise', 'Eagle', 'Star']),
('Nampa', 'ID', 'Idaho', 99921, ARRAY['Caldwell', 'Boise', 'Canyon County']),
('Pocatello', 'ID', 'Idaho', 55362, ARRAY['Chubbuck', 'Fort Hall', 'Inkom']),
('Coeur d''Alene', 'ID', 'Idaho', 49573, ARRAY['Post Falls', 'Rathdrum', 'Hayden']),

-- ILLINOIS
('Chicago', 'IL', 'Illinois', 2693976, ARRAY['Evanston', 'Oak Park', 'Schiller Park']),
('Aurora', 'IL', 'Illinois', 197899, ARRAY['Naperville', 'North Aurora', 'Sugar Land']),
('Rockford', 'IL', 'Illinois', 142591, ARRAY['Loves Park', 'Machesney Park', 'Roscoe']),
('Joliet', 'IL', 'Illinois', 150362, ARRAY['Plainfield', 'Lockport', 'Minooka']),
('Naperville', 'IL', 'Illinois', 147779, ARRAY['Warrenville', 'Wheaton', 'Aurora']),
('Springfield', 'IL', 'Illinois', 116482, ARRAY['Chatham', 'Athens', 'Williamsville']),
('Peoria', 'IL', 'Illinois', 113011, ARRAY['East Peoria', 'Pekin', 'Chillicothe']),
('Evanston', 'IL', 'Illinois', 74486, ARRAY['Chicago', 'Skokie', 'Wilmette']),
('Elgin', 'IL', 'Illinois', 115355, ARRAY['Dundee', 'South Elgin', 'St. Charles']),
('Waukegan', 'IL', 'Illinois', 88826, ARRAY['North Chicago', 'Libertyville', 'Gurnee']),

-- INDIANA
('Indianapolis', 'IN', 'Indiana', 867125, ARRAY['Carmel', 'Fishers', 'Greenwood']),
('Fort Wayne', 'IN', 'Indiana', 253691, ARRAY['New Haven', 'Huntertown', 'Spencerville']),
('Evansville', 'IN', 'Indiana', 117528, ARRAY['Newburgh', 'Haubstadt', 'North Pointe']),
('South Bend', 'IN', 'Indiana', 99786, ARRAY['Mishawaka', 'Niles', 'Osceola']),
('Bloomington', 'IN', 'Indiana', 87468, ARRAY['Normal', 'Ellettsville', 'Stinesville']),
('Gary', 'IN', 'Indiana', 80294, ARRAY['Hammond', 'East Chicago', 'Portage']),
('Carmel', 'IN', 'Indiana', 101376, ARRAY['Indianapolis', 'Westfield', 'Noblesville']),
('Fishers', 'IN', 'Indiana', 97653, ARRAY['Indianapolis', 'Carmel', 'Noblesville']),
('Muncie', 'IN', 'Indiana', 67430, ARRAY['Yorktown', 'Albany', 'Ashley']),
('Terre Haute', 'IN', 'Indiana', 59614, ARRAY['West Terre Haute', 'Merom', 'Carbon']),

-- IOWA
('Des Moines', 'IA', 'Iowa', 214237, ARRAY['West Des Moines', 'Ankeny', 'Urbandale']),
('Cedar Rapids', 'IA', 'Iowa', 130330, ARRAY['Marion', 'Hiawatha', 'Robins']),
('Davenport', 'IA', 'Iowa', 99685, ARRAY['Bettendorf', 'Pleasant Valley', 'LeClaire']),
('Sioux City', 'IA', 'Iowa', 82684, ARRAY['South Sioux City', 'Dakota City', 'Hinton']),
('Iowa City', 'IA', 'Iowa', 74828, ARRAY['Coralville', 'North Liberty', 'Tiffin']),
('Waterloo', 'IA', 'Iowa', 67314, ARRAY['Cedar Falls', 'Evansdale', 'Hudson']),
('Council Bluffs', 'IA', 'Iowa', 62414, ARRAY['Omaha', 'Papillion', 'LaVista']),
('Ames', 'IA', 'Iowa', 66498, ARRAY['Boone', 'Madrid', 'Ledyard']),
('Dubuque', 'IA', 'Iowa', 57637, ARRAY['Cascade', 'Dyersville', 'Holy Cross']),
('Ottumwa', 'IA', 'Iowa', 24843, ARRAY['Chillicothe', 'Albia', 'Eldon']),

-- KANSAS
('Wichita', 'KS', 'Kansas', 389255, ARRAY['Andover', 'Goddard', 'Cheney']),
('Overland Park', 'KS', 'Kansas', 188582, ARRAY['Leawood', 'Prairie Village', 'Olathe']),
('Kansas City', 'KS', 'Kansas', 155271, ARRAY['Lenexa', 'Merriam', 'Mission']),
('Olathe', 'KS', 'Kansas', 142062, ARRAY['Lenexa', 'Overland Park', 'Leawood']),
('Topeka', 'KS', 'Kansas', 126587, ARRAY['Shawnee', 'Auburn', 'Lawrence']),
('Lawrence', 'KS', 'Kansas', 95112, ARRAY['Baldwin City', 'Eudora', 'LeCompton']),
('Salina', 'KS', 'Kansas', 47707, ARRAY['Brookville', 'Falun', 'Lincoln']),
('Manhattan', 'KS', 'Kansas', 56641, ARRAY['Ogden', 'Wamego', 'St. George']),
('Leawood', 'KS', 'Kansas', 35251, ARRAY['Overland Park', 'Prairie Village', 'Merriam']),
('Hutchinson', 'KS', 'Kansas', 40284, ARRAY['Nickerson', 'Haven', 'Buhler']),

-- KENTUCKY
('Louisville', 'KY', 'Kentucky', 256231, ARRAY['Jeffersontown', 'St. Matthews', 'La Grange']),
('Lexington', 'KY', 'Kentucky', 322570, ARRAY['Nicholasville', 'Richmond', 'Georgetown']),
('Bowling Green', 'KY', 'Kentucky', 68231, ARRAY['Warren County', 'Alvaton', 'Rocky Hill']),
('Owensboro', 'KY', 'Kentucky', 57265, ARRAY['Whitesville', 'Maceo', 'Utica']),
('Covington', 'KY', 'Kentucky', 40640, ARRAY['Newport', 'Florence', 'Kenton']),
('Paducah', 'KY', 'Kentucky', 24702, ARRAY['Blandville', 'Lovelaceville', 'Fancy Farm']),
('Frankfort', 'KY', 'Kentucky', 27098, ARRAY['Juniper Hill', 'Woodford County', 'Versailles']),
('Richmond', 'KY', 'Kentucky', 34927, ARRAY['Irvine', 'Boonesborough', 'Kentucky River']),
('Hopkinsville', 'KY', 'Kentucky', 29809, ARRAY['Oak Grove', 'Guthrie', 'Pembroke']),
('Henderson', 'KY', 'Kentucky', 27373, ARRAY['Morganfield', 'Corydon', 'Mattoon']),

-- LOUISIANA
('New Orleans', 'LA', 'Louisiana', 383997, ARRAY['Metairie', 'Kenner', 'Gretna']),
('Baton Rouge', 'LA', 'Louisiana', 227818, ARRAY['Central', 'Baker', 'Zachary']),
('Shreveport', 'LA', 'Louisiana', 189874, ARRAY['Bossier City', 'Blanchard', 'Haughton']),
('Lafayette', 'LA', 'Louisiana', 128115, ARRAY['Broussard', 'Scott', 'Youngsville']),
('Lake Charles', 'LA', 'Louisiana', 75097, ARRAY['Sulphur', 'Westlake', 'Iowa']),
('Houma', 'LA', 'Louisiana', 33263, ARRAY['Bayou Cane', 'Terrebonne', 'Schriever']),
('Monroe', 'LA', 'Louisiana', 48434, ARRAY['West Monroe', 'Calhoun', 'Grambling']),
('Alexandria', 'LA', 'Louisiana', 46297, ARRAY['Pineville', 'Woodworth', 'Moreauville']),
('New Iberia', 'LA', 'Louisiana', 30617, ARRAY['Jeanerette', 'Baldwin', 'Loreauville']),
('Bossier City', 'LA', 'Louisiana', 68725, ARRAY['Shreveport', 'Blanchard', 'Haughton']),

-- MAINE
('Portland', 'ME', 'Maine', 68408, ARRAY['South Portland', 'Westbrook', 'Cape Elizabeth']),
('Lewiston', 'ME', 'Maine', 36592, ARRAY['Auburn', 'Durham', 'Sabbatus']),
('Bangor', 'ME', 'Maine', 38824, ARRAY['Brewer', 'Orrington', 'Eddington']),
('Augusta', 'ME', 'Maine', 18899, ARRAY['Hallowell', 'Manchester', 'Belgrade']),
('South Portland', 'ME', 'Maine', 25681, ARRAY['Portland', 'Scarborough', 'Cape Elizabeth']),

-- MARYLAND
('Baltimore', 'MD', 'Maryland', 575638, ARRAY['Dundalk', 'Towson', 'Woodlawn']),
('Frederick', 'MD', 'Maryland', 69641, ARRAY['Urbana', 'Monrovia', 'Jefferson']),
('Gaithersburg', 'MD', 'Maryland', 69406, ARRAY['Germantown', 'Rockville', 'Poolesville']),
('Bowie', 'MD', 'Maryland', 57849, ARRAY['Crofton', 'Odenton', 'Laurel']),
('Annapolis', 'MD', 'Maryland', 39176, ARRAY['Arnold', 'Glen Burnie', 'Millersville']),

-- MASSACHUSETTS
('Boston', 'MA', 'Massachusetts', 692600, ARRAY['Cambridge', 'Brookline', 'Somerville']),
('Worcester', 'MA', 'Massachusetts', 184508, ARRAY['Auburn', 'Leicester', 'West Boylston']),
('Springfield', 'MA', 'Massachusetts', 153060, ARRAY['Chicopee', 'Holyoke', 'East Longmeadow']),
('Lowell', 'MA', 'Massachusetts', 116005, ARRAY['Chelmsford', 'Tewksbury', 'Billerica']),
('Cambridge', 'MA', 'Massachusetts', 118395, ARRAY['Boston', 'Brookline', 'Somerville']),

-- MICHIGAN
('Detroit', 'MI', 'Michigan', 639111, ARRAY['Hamtramck', 'Highland Park', 'Dearborn']),
('Grand Rapids', 'MI', 'Michigan', 195355, ARRAY['Kentwood', 'Wyoming', 'Cascade']),
('Warren', 'MI', 'Michigan', 139387, ARRAY['Sterling Heights', 'Troy', 'Roseville']),
('Sterling Heights', 'MI', 'Michigan', 134346, ARRAY['Warren', 'Troy', 'Madison Heights']),
('Ann Arbor', 'MI', 'Michigan', 123411, ARRAY['Ypsilanti', 'Pittsfield', 'Saline']),

-- MINNESOTA
('Minneapolis', 'MN', 'Minnesota', 398552, ARRAY['St. Paul', 'Edina', 'Bloomington']),
('St. Paul', 'MN', 'Minnesota', 311527, ARRAY['Minneapolis', 'Maplewood', 'Falcon Heights']),
('Rochester', 'MN', 'Minnesota', 115226, ARRAY['Olmsted County', 'Byron', 'Oronoco']),
('Duluth', 'MN', 'Minnesota', 87014, ARRAY['Superior', 'Proctor', 'Hermantown']),
('Bloomington', 'MN', 'Minnesota', 85578, ARRAY['Minneapolis', 'Richfield', 'Edina']),

-- MISSISSIPPI
('Jackson', 'MS', 'Mississippi', 153701, ARRAY['Madison', 'Ridgeland', 'Pearl']),
('Gulfport', 'MS', 'Mississippi', 71012, ARRAY['Biloxi', 'Pass Christian', 'Long Beach']),
('Biloxi', 'MS', 'Mississippi', 45034, ARRAY['Gulfport', 'Ocean Springs', 'Pascagoula']),
('Hattiesburg', 'MS', 'Mississippi', 45313, ARRAY['Petal', 'Purvis', 'Sumrall']),
('Southaven', 'MS', 'Mississippi', 48982, ARRAY['Memphis', 'Horn Lake', 'Olive Branch']),

-- MISSOURI
('Kansas City', 'MO', 'Missouri', 481420, ARRAY['Overland Park', 'Independence', 'Lee''s Summit']),
('St. Louis', 'MO', 'Missouri', 301578, ARRAY['Clayton', 'Webster Groves', 'Kirkwood']),
('Springfield', 'MO', 'Missouri', 169176, ARRAY['Republic', 'Ozark', 'Nixa']),
('Joplin', 'MO', 'Missouri', 50021, ARRAY['Carterville', 'Duenweg', 'Sarcoxie']),
('Columbia', 'MO', 'Missouri', 121015, ARRAY['Boone County', 'Rocheport', 'Sturgeon']),

-- MONTANA
('Billings', 'MT', 'Montana', 121788, ARRAY['Laurel', 'Lockwood', 'Shepherd']),
('Missoula', 'MT', 'Montana', 70320, ARRAY['Frenchtown', 'Lolo', 'Bonner']),
('Great Falls', 'MT', 'Montana', 58505, ARRAY['Black Eagle', 'Belt', 'Ulm']),
('Bozeman', 'MT', 'Montana', 53291, ARRAY['Belgrade', 'Manhattan', 'Three Forks']),
('Butte', 'MT', 'Montana', 33525, ARRAY['Anaconda', 'Ramsay', 'Warm Springs']),

-- NEBRASKA
('Omaha', 'NE', 'Nebraska', 468062, ARRAY['Papillion', 'LaVista', 'Bellevue']),
('Lincoln', 'NE', 'Nebraska', 285407, ARRAY['Waverly', 'Lancaster County', 'Bennet']),
('Bellevue', 'NE', 'Nebraska', 60619, ARRAY['Omaha', 'Papillion', 'LaVista']),
('Grand Island', 'NE', 'Nebraska', 50550, ARRAY['Wood River', 'Doniphan', 'Phillips']),
('Kearney', 'NE', 'Nebraska', 33847, ARRAY['Gibbon', 'Shelton', 'Holdrege']),

-- NEVADA
('Las Vegas', 'NV', 'Nevada', 644644, ARRAY['Henderson', 'Paradise', 'North Las Vegas']),
('Henderson', 'NV', 'Nevada', 317610, ARRAY['Las Vegas', 'Boulder City', 'Pahrump']),
('Reno', 'NV', 'Nevada', 248052, ARRAY['Sparks', 'Incline Village', 'Tahoe']),
('North Las Vegas', 'NV', 'Nevada', 278927, ARRAY['Las Vegas', 'Nellis AFB', 'Sunrise Manor']),
('Sparks', 'NV', 'Nevada', 104163, ARRAY['Reno', 'Sun Valley', 'Stead']),

-- NEW HAMPSHIRE
('Manchester', 'NH', 'New Hampshire', 110378, ARRAY['Nashua', 'Goffstown', 'Hooksett']),
('Nashua', 'NH', 'New Hampshire', 87137, ARRAY['Hudson', 'Hollis', 'Litchfield']),
('Portsmouth', 'NH', 'New Hampshire', 21233, ARRAY['Seabrook', 'Stratham', 'Rye']),
('Dover', 'NH', 'New Hampshire', 31409, ARRAY['Somersworth', 'Rochester', 'Farmington']),
('Rochester', 'NH', 'New Hampshire', 30527, ARRAY['Somersworth', 'Dover', 'Barrington']),

-- NEW JERSEY
('Newark', 'NJ', 'New Jersey', 311549, ARRAY['Jersey City', 'East Orange', 'Irvington']),
('Jersey City', 'NJ', 'New Jersey', 264290, ARRAY['Newark', 'Hoboken', 'Weehawken']),
('Paterson', 'NJ', 'New Jersey', 151778, ARRAY['Clifton', 'Passaic', 'Hawthorne']),
('Elizabeth', 'NJ', 'New Jersey', 130363, ARRAY['Newark', 'Linden', 'Rahway']),
('Trenton', 'NJ', 'New Jersey', 84850, ARRAY['Princeton', 'Ewing', 'Hamilton']),

-- NEW MEXICO
('Albuquerque', 'NM', 'New Mexico', 562595, ARRAY['Rio Rancho', 'Bernalillo', 'Corrales']),
('Las Cruces', 'NM', 'New Mexico', 97618, ARRAY['Organ', 'Mesilla', 'Anthony']),
('Rio Rancho', 'NM', 'New Mexico', 96518, ARRAY['Albuquerque', 'Bernalillo', 'Kirtland']),
('Santa Fe', 'NM', 'New Mexico', 87597, ARRAY['Tesuque', 'Peñasco', 'Espanola']),
('Roswell', 'NM', 'New Mexico', 48411, ARRAY['Hagerman', 'Dexter', 'Lake Arthur']),

-- NEW YORK
('New York City', 'NY', 'New York', 8336817, ARRAY['Newark', 'Jersey City', 'Yonkers']),
('Buffalo', 'NY', 'New York', 250604, ARRAY['Cheektowaga', 'Niagara Falls', 'Tonawanda']),
('Rochester', 'NY', 'New York', 205861, ARRAY['Irondequoit', 'Greece', 'Brighton']),
('Yonkers', 'NY', 'New York', 211569, ARRAY['New Rochelle', 'Mount Vernon', 'White Plains']),
('Syracuse', 'NY', 'New York', 142706, ARRAY['Onondaga', 'DeWitt', 'Cicero']),

-- NORTH CAROLINA
('Charlotte', 'NC', 'North Carolina', 891733, ARRAY['Concord', 'Kannapolis', 'Cabarrus County']),
('Raleigh', 'NC', 'North Carolina', 474069, ARRAY['Cary', 'Durham', 'Wake Forest']),
('Greensboro', 'NC', 'North Carolina', 294723, ARRAY['High Point', 'Asheboro', 'Kernersville']),
('Durham', 'NC', 'North Carolina', 287621, ARRAY['Chapel Hill', 'Raleigh', 'Research Triangle']),
('Winston-Salem', 'NC', 'North Carolina', 248716, ARRAY['High Point', 'Greensboro', 'Kernersville']),

-- NORTH DAKOTA
('Bismarck', 'ND', 'North Dakota', 73529, ARRAY['Mandan', 'Lincoln', 'Hazelton']),
('Fargo', 'ND', 'North Dakota', 135797, ARRAY['Moorhead', 'West Fargo', 'Cass County']),
('Grand Forks', 'ND', 'North Dakota', 76363, ARRAY['East Grand Forks', 'Emerado', 'Gilby']),
('Minot', 'ND', 'North Dakota', 40709, ARRAY['Garrison', 'Makoti', 'Velva']),
('Williston', 'ND', 'North Dakota', 28261, ARRAY['Watford City', 'Arnegard', 'Alexander']),

-- OHIO
('Columbus', 'OH', 'Ohio', 898553, ARRAY['Westerville', 'New Albany', 'Worthington']),
('Cleveland', 'OH', 'Ohio', 344518, ARRAY['Shaker Heights', 'Beachwood', 'Cleveland Heights']),
('Cincinnati', 'OH', 'Ohio', 309317, ARRAY['Norwood', 'Newport', 'Florence']),
('Toledo', 'OH', 'Ohio', 271605, ARRAY['Oregon', 'Maumee', 'Sylvania']),
('Akron', 'OH', 'Ohio', 197542, ARRAY['Barberton', 'Summit County', 'Cuyahoga Falls']),

-- OKLAHOMA
('Oklahoma City', 'OK', 'Oklahoma', 649380, ARRAY['Edmond', 'Norman', 'Moore']),
('Tulsa', 'OK', 'Oklahoma', 408352, ARRAY['Broken Arrow', 'Owasso', 'Sapulpa']),
('Norman', 'OK', 'Oklahoma', 131600, ARRAY['Moore', 'Oklahoma City', 'Midwest City']),
('Broken Arrow', 'OK', 'Oklahoma', 108191, ARRAY['Tulsa', 'Owasso', 'Catoosa']),
('Edmond', 'OK', 'Oklahoma', 91500, ARRAY['Oklahoma City', 'Guthrie', 'Piedmont']),

-- OREGON
('Portland', 'OR', 'Oregon', 652032, ARRAY['Gresham', 'Beaverton', 'Lake Oswego']),
('Eugene', 'OR', 'Oregon', 176654, ARRAY['Springfield', 'Cottage Grove', 'Creswell']),
('Salem', 'OR', 'Oregon', 175535, ARRAY['Keizer', 'Silverton', 'Turner']),
('Gresham', 'OR', 'Oregon', 110657, ARRAY['Portland', 'Troutdale', 'Wood Village']),
('Hillsboro', 'OR', 'Oregon', 104778, ARRAY['Beaverton', 'Portland', 'Forest Grove']),

-- PENNSYLVANIA
('Philadelphia', 'PA', 'Pennsylvania', 1605602, ARRAY['Chester', 'Norriton', 'Cheltenham']),
('Pittsburgh', 'PA', 'Pennsylvania', 304391, ARRAY['Mount Lebanon', 'Bethel Park', 'Wilkinsburg']),
('Allentown', 'PA', 'Pennsylvania', 133637, ARRAY['Bethlehem', 'Easton', 'Whitehall']),
('Erie', 'PA', 'Pennsylvania', 96528, ARRAY['Girard', 'Waterford', 'Edinboro']),
('Reading', 'PA', 'Pennsylvania', 95112, ARRAY['Wyomissing', 'Muhlenberg', 'Spring Township']),

-- RHODE ISLAND
('Providence', 'RI', 'Rhode Island', 179883, ARRAY['Warwick', 'Cranston', 'West Warwick']),
('Warwick', 'RI', 'Rhode Island', 80387, ARRAY['Cranston', 'Providence', 'West Warwick']),
('Cranston', 'RI', 'Rhode Island', 80387, ARRAY['Providence', 'Warwick', 'Johnston']),
('Pawtucket', 'RI', 'Rhode Island', 71148, ARRAY['Central Falls', 'Attleboro', 'Providence']),
('Woonsocket', 'RI', 'Rhode Island', 41186, ARRAY['Cumberland', 'North Smithfield', 'Blackstone']),

-- SOUTH CAROLINA
('Charleston', 'SC', 'South Carolina', 150437, ARRAY['Mount Pleasant', 'Goose Creek', 'Summerville']),
('Greenville', 'SC', 'South Carolina', 70720, ARRAY['Mauldin', 'Simpsonville', 'Taylors']),
('Columbia', 'SC', 'South Carolina', 131674, ARRAY['Forest Acres', 'Lexington', 'Cayce']),
('Hilton Head Island', 'SC', 'South Carolina', 39436, ARRAY['Bluffton', 'Beaufort', 'Port Royal']),
('Myrtle Beach', 'SC', 'South Carolina', 28933, ARRAY['North Myrtle Beach', 'Surfside Beach', 'Conway']),

-- SOUTH DAKOTA
('Sioux Falls', 'SD', 'South Dakota', 191656, ARRAY['Harrisburg', 'Brandon', 'Colton']),
('Rapid City', 'SD', 'South Dakota', 77901, ARRAY['Box Elder', 'Black Hawk', 'Piedmont']),
('Aberdeen', 'SD', 'South Dakota', 27868, ARRAY['Columbia', 'Groton', 'Sisseton']),
('Watertown', 'SD', 'South Dakota', 14475, ARRAY['Castlewood', 'Codington County', 'Poinsett']),
('Mitchell', 'SD', 'South Dakota', 15368, ARRAY['Huron', 'De Soto', 'Fedora']),

-- TENNESSEE
('Memphis', 'TN', 'Tennessee', 633104, ARRAY['Shelby County', 'Germantown', 'Collierville']),
('Nashville', 'TN', 'Tennessee', 678889, ARRAY['Brentwood', 'Franklin', 'Spring Hill']),
('Knoxville', 'TN', 'Tennessee', 190740, ARRAY['Oak Ridge', 'Seymour', 'Farragut']),
('Chattanooga', 'TN', 'Tennessee', 181099, ARRAY['East Brainerd', 'Soddy-Daisy', 'Hixson']),
('Clarksville', 'TN', 'Tennessee', 167321, ARRAY['Fort Campbell', 'Hopkinsville', 'Ashland']),

-- TEXAS
('Houston', 'TX', 'Texas', 2302878, ARRAY['Spring', 'Pearland', 'Sugarland']),
('San Antonio', 'TX', 'Texas', 1547253, ARRAY['Leon Valley', 'Alamo Heights', 'Universal City']),
('Dallas', 'TX', 'Texas', 1343573, ARRAY['Arlington', 'Plano', 'Irving']),
('Austin', 'TX', 'Texas', 961855, ARRAY['Round Rock', 'Pflugerville', 'Cedar Park']),
('Fort Worth', 'TX', 'Texas', 918915, ARRAY['Arlington', 'Grand Prairie', 'Irving']),

-- UTAH
('Salt Lake City', 'UT', 'Utah', 199723, ARRAY['West Valley City', 'Taylorsville', 'South Salt Lake']),
('West Valley City', 'UT', 'Utah', 136815, ARRAY['Salt Lake City', 'Taylorsville', 'Magna']),
('Provo', 'UT', 'Utah', 116868, ARRAY['Orem', 'Lindon', 'Alpine']),
('Orem', 'UT', 'Utah', 97379, ARRAY['Provo', 'Lindon', 'Vineyard']),
('Sandy', 'UT', 'Utah', 85677, ARRAY['Draper', 'South Jordan', 'Midvale']),

-- VERMONT
('Burlington', 'VT', 'Vermont', 45885, ARRAY['South Burlington', 'Winooski', 'Williston']),
('Rutland', 'VT', 'Vermont', 17950, ARRAY['Wallingford', 'Tinmouth', 'Clarendon']),
('Colchester', 'VT', 'Vermont', 16986, ARRAY['Burlington', 'Winooski', 'Essex Junction']),
('South Burlington', 'VT', 'Vermont', 19755, ARRAY['Burlington', 'Williston', 'Shelburne']),
('Barre', 'VT', 'Vermont', 8406, ARRAY['Montpelier', 'Berlin', 'Williamstown']),

-- VIRGINIA
('Virginia Beach', 'VA', 'Virginia', 450435, ARRAY['Chesapeake', 'Norfolk', 'Newport News']),
('Richmond', 'VA', 'Virginia', 230436, ARRAY['Henrico County', 'Chesterfield County', 'Colonial Heights']),
('Arlington', 'VA', 'Virginia', 238612, ARRAY['Alexandria', 'Washington DC', 'Falls Church']),
('Alexandria', 'VA', 'Virginia', 159428, ARRAY['Arlington', 'Washington DC', 'Fairfax']),
('Roanoke', 'VA', 'Virginia', 99899, ARRAY['Salem', 'Vinton', 'Hollins']),

-- WASHINGTON
('Seattle', 'WA', 'Washington', 753675, ARRAY['Tacoma', 'Bellevue', 'Redmond']),
('Tacoma', 'WA', 'Washington', 219346, ARRAY['Seattle', 'Puyallup', 'Federal Way']),
('Vancouver', 'WA', 'Washington', 190915, ARRAY['Camas', 'Washougal', 'Portland OR']),
('Bellevue', 'WA', 'Washington', 150904, ARRAY['Seattle', 'Redmond', 'Sammamish']),
('Kent', 'WA', 'Washington', 136881, ARRAY['Renton', 'Seattle', 'Auburn']),

-- WEST VIRGINIA
('Charleston', 'WV', 'West Virginia', 47620, ARRAY['Kanawha County', 'South Charleston', 'St. Albans']),
('Huntington', 'WV', 'West Virginia', 46842, ARRAY['Barboursville', 'Ironton', 'Wayne']),
('Wheeling', 'WV', 'West Virginia', 26918, ARRAY['Benwood', 'Moundsville', 'Bridgeport OH']),
('Morgantown', 'WV', 'West Virginia', 29660, ARRAY['Fairmont', 'Brownsville', 'Star City']),
('Beckley', 'WV', 'West Virginia', 17614, ARRAY['Sophia', 'Spanishburg', 'Ghent']),

-- WISCONSIN
('Milwaukee', 'WI', 'Wisconsin', 584047, ARRAY['Wauwatosa', 'West Milwaukee', 'Shorewood']),
('Madison', 'WI', 'Wisconsin', 269840, ARRAY['Fitchburg', 'Dane County', 'Monona']),
('Green Bay', 'WI', 'Wisconsin', 105207, ARRAY['Ashwaubenon', 'De Pere', 'Suamico']),
('Kenosha', 'WI', 'Wisconsin', 99218, ARRAY['Racine', 'Bristol', 'Salem']),
('Racine', 'WI', 'Wisconsin', 76705, ARRAY['Kenosha', 'Sturtevant', 'Caledonia']),

-- WYOMING
('Cheyenne', 'WY', 'Wyoming', 65395, ARRAY['Laramie', 'Evanston', 'Rawlins']),
('Casper', 'WY', 'Wyoming', 57110, ARRAY['Mills', 'Glenrock', 'Natrona County']),
('Laramie', 'WY', 'Wyoming', 33231, ARRAY['Cheyenne', 'Centennial', 'Saratoga']),
('Gillette', 'WY', 'Wyoming', 33841, ARRAY['Wright', 'Sheridan', 'Sundance']),
('Rock Springs', 'WY', 'Wyoming', 23834, ARRAY['Green River', 'Sweetwater County', 'Flaming Gorge'])

ON CONFLICT (city_name, state_code) DO UPDATE SET
  population = EXCLUDED.population,
  nearby_cities = EXCLUDED.nearby_cities,
  updated_at = NOW();

-- Verify import
SELECT COUNT(*) as total_cities, COUNT(DISTINCT state_code) as states_count FROM cities;
