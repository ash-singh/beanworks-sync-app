# Use a lighter version of Node as a parent image
FROM node:11.10.0-stretch

#ARG DOCKER_UID=1000
#RUN usermod -u ${DOCKER_UID} node
#
#USER node

RUN mkdir /react-app

# Set the working directory to /client
WORKDIR /react-app

# copy package.json into the container at /client
COPY package*.json /react-app/

RUN npm cache verify

# install dependencies
RUN npm install

# Copy the current directory contents into the container at /client
COPY . /react-app/

# Make port 3000 available to the world outside this container
EXPOSE 3000

# Run the app when the container launches
CMD ["npm", "start"]